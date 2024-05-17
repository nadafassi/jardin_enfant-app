<?php

namespace KarimBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Abonnement;
use AppBundle\Entity\Enfant;
use AppBundle\Entity\Jardin;
use AppBundle\Entity\Messages;
use AppBundle\Entity\Parents;
use AppBundle\Entity\Reclamation;
use AppBundle\Entity\Remarque;
use AppBundle\Entity\Tuteur;
use AppBundle\Entity\User;
use AppBundle\Repository\RemarqueRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;

/**
 * WebService controller.
 *
 * @Route("Api")
 */
class WebServicesController extends Controller
{

    /**
     * Lists my remarks entities.
     *
     * @Route("/listrem/{par}", name="remarques_api",methods={"GET"})
     */
    public function listremarquesAction($par)
    {
        //the parent to check his children remarks
        $em = $this->getDoctrine()->getManager();

        $remarques = $em->getRepository(Remarque::class)->getremarques($par);

        $encoder = new JsonEncoder();


        return new JsonResponse($remarques);

    }

    /**
     * Lists tut my remarks entities.
     *
     * @Route("/listmyrem/{tut}", name="myremarques_api",methods={"GET"})
     */
    public function listtutremarquesAction($tut)
    {

        //get the remarks that have been added by a given tutor (tut is the id of the tutor)
        $em = $this->getDoctrine()->getManager();

        $remarques = $em->getRepository(Remarque::class)->gettutremarques($tut);

        $encoder = new JsonEncoder();


        return new JsonResponse($remarques);

    }

    /**
     * Lists all parent entities.
     *
     * @Route("/listpar", name="parents_index_api")
     */
    public function indexAction()
    {
        //list parent but i don't think it s usefull
        //maybe usefull for reponsable jardin and the admin
        $em = $this->getDoctrine()->getManager();

        $parents = $em->getRepository('AppBundle:Parents')->find(8);

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId(); // Change this to a valid method of your object
        });
        $serializer = new Serializer(array($normalizer));
        $formatted = $serializer->normalize($parents);


        return new JsonResponse($formatted);

    }


    /**
     *add remarks
     *
     * @Route("/addrem", name="add_remark_api")
     */
    public function Adddrem(Request $request)
    {

        //for the tutor to add a remar while the remarks are binded to abonnement i had to put the abonnement id in the request

        $em = $this->getDoctrine()->getManager();
        $tut = $em->getRepository(Tuteur::class)->find($request->get("tut"));
        $abo = $em->getRepository(Abonnement::class)->find($request->get("abo"));

        $date = new DateTime("now");

        $desc = $request->get("descr");

        $remark = new Remarque();
        $remark->setAbonnement($abo);
        $remark->setDate($date);
        $remark->setDescription($desc);
        $remark->setTuteur($tut);
        $em->persist($remark);
        $em->flush();
        $this->sendmail($request->get("enf"),$tut->getId(),$desc,$date);



        if ($em->contains($remark)) {
            return new JsonResponse("success");
        } else {
            return new JsonResponse("error");
        }


    }


    public function sendmail($enf,$tutid,$desc,$date){

        $em=$this->getDoctrine()->getManager();

        $enfant=$em->getRepository(Enfant::class)->find($enf);
        $parid=$enfant->getParent()->getId();
        $par=$em->getRepository(Parents::class)->findOneBy(["id"=>$parid]);

        $tut=$em->getRepository(Tuteur::class)->find($tutid);
        $nomtut=$tut->getNom()." ".$tut->getPrenom();


        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 587, 'tls')
            ->setUsername('trizouni1@gmail.com')
            ->setPassword('tmdpbiphihxcgyqy');

        try{
            $mailer = new \Swift_Mailer($transport);

            $ms=(new \Swift_Message('Vous avez une nouvelle remarque pour votre enfant'))
                ->setFrom('raed.bahri@esprit.tn')
                ->setTo($par->getEmail())

                ->setBody("<h3>".$enfant->getNom()." ".$enfant->getPrenom()." a recu une nouvelle remarque de la part M/Mme ".$nomtut."</h3>".
                    "<br>".$desc."<br>Attribué le :".$date->format('Y-m-d H:i:s')."",'text/html');


            $mailer->send($ms);
        }catch (Exception $ex){
            return new JsonResponse($ex);

        }

        return new JsonResponse($ms->getBody());

    }

    /**
     *add reclam
     *
     * @Route("/addreclam", name="add_reclam_api")
     */
    public function sendreclamAction(Request $request)
    {

        //send reclam will take most of data from db

        $em = $this->getDoctrine()->getManager();
        $parent = $em->getRepository(Parents::class)->find($request->get("par"));
        $reclam = new Reclamation();

        $reclam->setParent($parent);

        $reclam->setDescription($request->get("description"));
        $reclam->setDate(new DateTime());
        $reclam->setTitre($request->get("titre"));
        $reclam->setNom($parent->getNom() . " " . $parent->getPrenom());
        $reclam->setNumtel($parent->getNumtel());
        $reclam->setEtat("en attente");
        $reclam->setMail($parent->getEmail());

        $em->persist($reclam);
        $em->flush();

        if ($em->contains($reclam)) {
            return new JsonResponse("success");
        } else {
            return new JsonResponse("error");
        }


    }

    /**
     *getparent
     *
     * @Route("/getparent", name="get_parent_api")
     */
    public function getParentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $parent = $em->getRepository(Parents::class)->getparent($request->get("par"));

        //get the parent object to edit his profile
        return new JsonResponse($parent);
    }


    /**
     *edit profile
     *
     * @Route("/editparent", name="edit_parent_api")
     */
    //edit prodile parent
    public function editparentAction(Request $request)
    {

        $parent = new Parents();
        $em = $this->getDoctrine()->getManager();
        $username = $request->get("username");
        $email = $request->get("email");
        $password = $request->get("password");
        $nom = $request->get("nom");
        $prenom = $request->get("prenom");
        $numtel = $request->get("numtel");
        $adresse = $request->get("adresse");
        //got all request param

        $parent = $em->getRepository(Parents::class)->find($request->get("par"));
        //let s take the parent by id and change the what should be changer

        $parent->setUsername($username);
        $parent->setEmail($email);

        if (!empty ($password) || $password != null) {
            $parent->setPlainPassword($password);
        }
        $parent->setNom($nom);
        $parent->setPrenom($prenom);
        $parent->setNumtel($numtel);
        $parent->setAdresse($adresse);

        $parent->setEnabled(true);
        try {
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($parent);
        } catch (Exception $ex) {
            return new JsonResponse(false);
        }

        //i ve tested it and it works


        return new JsonResponse(true);
    }


    /**
     * testusername
     * @Route("/testusername",name="test_user_api")
     */
        //to test if username or mail exist
        //needed for every prodile editing
    public function testuserAction(Request $request)
    {
        $user_manager = $this->get('fos_user.user_manager');
        $user = $user_manager->findUserByUsername($request->get("username"));
        if ($user != null) {
            return new JsonResponse("Exist");
        } else {
            return new JsonResponse("OK");
        }

    }
    /**
     * testmail
     * @Route("/testemail",name="test_mail_api")
     */
    //to test if username or mail exist
    //needed for every prodile editing
    public function testusermailAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findBy(array("email"=> $request->get("email")));
        if ($user != null) {
            return new JsonResponse("Exist");
        } else {
            return new JsonResponse("OK");
        }

    }




    // for parent


    /**
     *listjars
     *
     * @Route("/jardmess", name="listjarsmess_api")
     */
    public function jardlistAction(Request $request)
    {
        //for the parent to get the kindergartens where his child have a subscription
        $em = $this->getDoctrine()->getManager();
        $jars = $em->getRepository(Messages::class)->getlistjard($request->get("par"));


        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId(); // Change this to a valid method of your object
        });
        $serializer = new Serializer(array($normalizer));
        $formatted = $serializer->normalize($jars);
        return new JsonResponse($formatted);

    }

    /**
     *listmessages
     *
     * @Route("/mymsg", name="list_messages_api")
     */
    public function message(Request $request)
    {
        //get messages sent filter in the mobile

        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(Messages::class)->getjardmess($request->get("par"), $request->get("jar"));
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId(); // Change this to a valid method of your object
        });
        $serializer = new Serializer(array($normalizer));
        $formatted = $serializer->normalize($messages);
        return new JsonResponse($formatted);


    }


    /**
     *listmessages
     *
     * @Route("/usermlist", name="list_muser_api")
     */
    public function userlist(Request $request)
    {

        //user list for resp jar
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(Messages::class)->getusermlist($request->get("jar"));
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId(); // Change this to a valid method of your object
        });
        $serializer = new Serializer(array($normalizer));
        $formatted = $serializer->normalize($messages);
        return new JsonResponse($formatted);


    }


    /**
     *user credentials
     *
     * @Route("/usercred", name="user_credential_api")
     */
    public function usercredential(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $username = $request->get("username");
        $user = $em->getRepository(User::class)->finduser($username);
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId(); // Change this to a valid method of your object
        });
        $serializer = new Serializer(array($normalizer));
        $formatted = $serializer->normalize($user);


        return new JsonResponse($formatted);
    }


    /**
     *listmessages
     *
     * @Route("/sendmsg", name="send_msg_api")
     */
    public function sendmsg(Request $request)
    {

        //send message valid for both resp and parent have just to change the sender id depending of the user

        $em = $this->getDoctrine()->getManager();
        $message = new Messages();
        $parent = $em->getRepository(Parents::class)->find($request->get("par"));
        $sender = $em->getRepository(User::class)->find($request->get("sender"));
        $jardin = $em->getRepository(Jardin::class)->find($request->get("jard"));
        $message->setJardin($jardin);
        $time = new DateTime();
        $message->setDate($time->format('Y-m-d H:i:s'));
        $message->setSender($sender);
        $message->setParent($parent);
        $message->setMsg($request->get("msg"));


        $em->persist($message);
        $em->flush();

        if ($em->contains($message)) {
            return new JsonResponse("true");
        } else {
            return new JsonResponse("false");
        }


    }


    /**
     * @Route("/listeenfjar/{id}", name="enfjar",methods={"GET"})
     */

    public function listenfjardinAction(Request $request, $id)
    {
        //for the tutor when his going to add remarks he will get the childrend subscribed in his kindergarten
        $em = $this->getDoctrine()->getManager();

        $tut = $em->getRepository(Tuteur::class)->find($id);

        $abonnement = $em->getRepository(Enfant::class)->getenfantjardin($tut->getJardin()->getId());

        return new JsonResponse($abonnement);
    }





}
