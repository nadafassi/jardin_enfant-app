<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Evenement;
use AppBundle\Entity\Jardin;
use AppBundle\Entity\Reclamation;
use AppBundle\Entity\Responsable;
use AppBundle\Entity\Tuteur;
use AppBundle\Entity\User;
use FOS\UserBundle\Util\PasswordUpdaterInterface;
use KarimBundle\Form\ReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\SelfSaltingEncoderInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need

     /*   $us=new User();
        $us->setPlainPassword("admin");
        $us->setUsername("admin");
        $us->setEmail("admin@admin.com");
        $us->setEnabled(true);
        $us->addRole("ROLE_ADMIN");
        $this->getDoctrine()->getManager()->persist($us);
        $this->getDoctrine()->getManager()->flush();
*/

        $events=$this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //if it s parent who sent this reclam he will be saved to database
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            if($user!=null){
                $reclamation->setParent($user);
            }

            $em = $this->getDoctrine()->getManager();
            $time=new \DateTime();
            $reclamation->setDate($time);
            $em->persist($reclamation);
            $em->flush();

            return $this->redirectToRoute('homepage', array('id' => $reclamation->getId()));
        }

        return $this->render('default/index.html.twig', array(
            'reclamation' => $reclamation,
            'form' => $form->createView(),
      'events'=>$events  ));



    }
    /**
     * @Route("/about", name="aboutus")
     */
    public function aboutAction(){

    }

    /**
     * @Route("/signin", name="user_login")
     */
    public function loginAction(Request $request){
        // i thought it was in the parent controller but it s here so if u want to check the other comment go there lol
        $username=$request->get('username');
        $password=$request->get('password');

        if($request->isMethod("GET")){
            return $this->render("default/login.html.twig",array("msg"=>""));
        }else{

            // Retrieve the security encoder of symfony
            $factory = $this->get('security.encoder_factory');


            $user_manager = $this->get('fos_user.user_manager');
            //$user = $user_manager->findUserByUsername($username);
            // Or by yourself
            $user = $this->getDoctrine()->getManager()->getRepository("AppBundle:User")
                ->findOneBy(array('username' => $username));
            /// End Retrieve user

            // Check if the user exists !
            if(!$user){
                return $this->render("default/login.html.twig",array("msg"=>"username non trouvé"));
            }

            /// Start verification
            $encoder = $factory->getEncoder($user);
            $salt = $user->getSalt();

            if(!$encoder->isPasswordValid($user->getPassword(), $password, $salt)) {
                return $this->render("default/login.html.twig",array("msg"=>"username ou mot de passe incorrect"));
            }
            /// End Verification

            // The password matches ! then proceed to set the user in session

            //Handle getting or creating the user entity likely with a posted form
            // The third parameter "main" can change according to the name of your firewall in security.yml
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            // If the firewall name is not main, then the set value would be instead:
            // $this->get('session')->set('_security_XXXFIREWALLNAMEXXX', serialize($token));
            $this->get('session')->set('_security_main', serialize($token));

            // Fire the login event manually
            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

           // $us=$this->container->get('security.token_storage')->getToken()->getUser();

            if ($this->container->get('security.authorization_checker')->isGranted("ROLE_RESPONSABLE")) {
                // SUPER_ADMIN roles go to the `admin_home` route
                return $this->redirectToRoute("dashboard");
            }elseif($this->container->get('security.authorization_checker')->isGranted('ROLE_PARENT')) {
                // Everyone else goes to the `home` route
                return $this->redirect("/");
            }

        }
    }



    /**
     * @Route("/Api/login/{username}/{password}", name="java_login")
     */
    public function signinAction($username,$password){
        $serializer = new Serializer([new ObjectNormalizer()]);
        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
        $user = $user_manager->findUserByUsername($username);
        if(!$user){
            $formatted = $serializer->normalize("Error");
            return new JsonResponse($formatted);
        }
        $encoder = $factory->getEncoder($user);


        //$user = $this->getDoctrine()->getRepository(User::class)->findByUsername($username);
        if(!$user)
            $bool=false;
        else
            $bool = ($encoder->isPasswordValid($user->getPassword(),$password,$user->getSalt())) ? true : false;



        if($bool){
            $formatted = $serializer->normalize("Success");
        }else{
            $formatted = $serializer->normalize("Error");

        }




        return new JsonResponse($formatted);


    }



    /**
     * @Route("/Api/addtutor/{idjar}/{email}/{username}/{password}/{nom}/{prenom}/{sexe}", name="tutor")
     */
        public function AddtutorAction($idjar,$email,$username,$password,$nom,$prenom,$sexe){
            $tut=new Tuteur();
            $tut->addRole("ROLE_TUTEUR");
            $tut->setUsername($username);
            $tut->setEmail($email);
            $tut->setPlainPassword($password);
            $tut->setNom($nom);
            $tut->setPrenom($prenom);
            $tut->setSexe($sexe);
            $tut->setJardin($this->getDoctrine()->getManager()->getRepository(Jardin::class)->find($idjar));


            try{
                $em=$this->getDoctrine()->getManager();
                $em->persist($tut);
                $em->flush();
            }catch (Exception $ex){
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($ex);
                return new JsonResponse($formatted);

            }


            //http://127.0.0.1:8000/Api/addtutor/2/ferid.chatti@gmail.com/frida/ferid123/ferid/chatti/homme

            $serializer = new Serializer([new ObjectNormalizer()]);

            $formatted = $serializer->normalize("done");
            return new JsonResponse($formatted);

        }

    /**
     * @Route("/Api/jardin/{id}", name="jardin_connecte")
     */
    public function jardinAction($id){
     $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT t
    FROM AppBundle:Jardin t,AppBundle:Responsable c WHERE t.id=c.jardin and c.id=:id'
        )
            ->setParameter('id',$id);

        $jardin = $query->getArrayResult();

        return new JsonResponse($jardin[0]);
    }
}
