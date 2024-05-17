<?php

namespace KarimBundle\Controller;

use AppBundle\Entity\Jardin;
use AppBundle\Entity\Messages;
use AppBundle\Entity\Parents;
use KarimBundle\Form\MessagesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Message controller.
 *
 * @Route("messages")
 */
class MessagesController extends Controller
{
    /**
     * Lists all message entities responsable.
     *
     * @Route("/msgs/{id}", name="messages_resp",defaults={"id"=null},methods={"GET","POST"})
     */
    public function lsAction($id){
        //this action is for the responsable jardin to see the incoming messages




        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $tab=$this->getDoctrine()->getManager()->getRepository(Messages::class)->getallmess($user->getJardin()->getId());
        if($id!=null){
            $mess=$this->getDoctrine()->getManager()->getRepository(Messages::class)->getmessages($id);
            return $this->render('@Karim/messages/index.html.twig',array("messages"=>$tab,"mess"=>$mess));
        }
        return $this->render('@Karim/messages/index.html.twig',array("messages"=>$tab));
    }

    /**
     * Lists all message entities.
     *
     * @Route("/ajouter", name="sendmsg",methods={"POST"})
     */
    public function sendAction(Request $request){
        // this action is for the parent to send message to the kidergarten

                $em=$this->getDoctrine()->getManager();
                $message = new Messages();



                $time=new \DateTime();
                $message->setMsg($request->get("msg"));
                $message->setDate($time->format('Y-m-d H:i:s'));

                $user = $this->container->get('security.token_storage')->getToken()->getUser();
                    $message->setJardin($this->getDoctrine()->getRepository(Jardin::class)->find($request->get("jarid")));
                    $message->setParent($user);
                    $message->setSender($user);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($message);
                    $em->flush();

                return $this->redirectToRoute('messages_index', array('id' =>$request->get("jarid")));




        }

    /**
     * Lists all message entities.
     *
     * @Route("/{id}", name="messages_index",defaults={"id" = null},methods={"GET","POST-"})
     */
    public function indexAction($id)
    {
        //this action is for the parent to see the message from the admin

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $jardin=array();
        $jardin=$this->getDoctrine()->getManager()->getRepository(Jardin::class)->getme($user->getId());
        //$messages=$this->getDoctrine()->getManager()->getRepository(Messages::class)->getmine($user->getId(),$jardin);

        if($id!=null){
            $mess=$this->getDoctrine()->getManager()->getRepository(Messages::class)->getmine($user->getId(),$id);
            return $this->render('@Karim/messages/show.html.twig',array("jardin"=>$jardin,"messages"=>$mess,"jarid"=>$id));
        }





        return $this->render('@Karim/messages/show.html.twig', array(
            "jardin"=>$jardin,

        ));
    }







    /**
     * add msg.
     *
     * @Route("/addmess", name="addmess",methods={"POST"})
     */
    public function addmessction(Request $request)
    {
        //this action if for the responsable jardin to send message to a parent

        $mes=$request->get('msg');
        $message=new Messages();

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if(in_array("ROLE_RESPONSABLE" , $user->getRoles())) {

            $message->setJardin($user->getJardin());

        }
        $parid= $request->get("id");

        $message->setParent($this->getDoctrine()->getManager()->getRepository(Parents::class)->find($parid));
        $time=new \DateTime();
        $message->setDate($time->format('Y-m-d H:i:s'));
        $message->setSender($user);
        $message->setMsg($mes);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 587, 'tls')
            ->setUsername('trizouni1@gmail.com')
            ->setPassword('tmdpbiphihxcgyqy');
        $mailer = new \Swift_Mailer($transport);

        $ms=(new \Swift_Message('Votre Message a eté envoyée avec success '))
            ->setFrom('raed.bahri@esprit.tn')
            ->setTo("karim-nar@live.fr")

            //  ->setBody('<h3> Bonjour  </h3>' . $contact->getNom()."Votre Message a eté envoyée avec success",'text/html');

            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'default/mail.html.twig',
                    ['nom' => $message->getParent()->getNom()
                    ,"mess"=>$message->getMsg()]

                ),
                'text/html'
            );
       // $mailer->send($ms);





        return $this->redirectToRoute('messages_resp',array("id"=>$parid));
    }

    /**
     * Creates a new message entity.
     *
     * @Route("/new", name="messages_new")
     */
    public function newAction(Request $request)
    {
        //this action is useless

        $em=$this->getDoctrine()->getManager();
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $time=new \DateTime();
            $message->setDate($time->format('Y-m-d H:i:s'));


            $id=4;


            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            if($user!=null){
                $message->setParent($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();
            }else{
                $message->setParent( $em->getRepository(Parents::class)->find($id));
            }




            return $this->redirectToRoute('messages_show', array('id' => $message->getId()));
        }

        return $this->render('@Karim/messages/new1.html.twig', array(
            'message' => $message,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a message entity.
     *
     * @Route("/{id}", name="messages_show",methods={"GET","HEAD"})
     */
    public function showAction(Messages $message)
    {
        //this action is more than useless
        $deleteForm = $this->createDeleteForm($message);

        return $this->render('@Karim/messages/show.html.twig', array(
            'message' => $message,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing message entity.
     *
     * @Route("/{id}/edit", name="messages_edit",methods={"GET","POST"})
     */
    public function editAction(Request $request, Messages $message)
    {
        //this one is to edit a message so it s useless too
        $deleteForm = $this->createDeleteForm($message);
        $editForm = $this->createForm(MessagesType::class, $message);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->merge($message);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('messages_show', array('id' => $message->getId()));
        }

        return $this->render('@Karim/messages/edit.html.twig', array(
            'message' => $message,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a message entity.
     *
     * @Route("Delete/{id}", name="messages_delete",methods={"DELETE"})
     */
    public function deleteAction(Request $request, Messages $message)
    {
        // i m not giving this option so this one too is useless maybe it s more than useless XD
        $form = $this->createDeleteForm($message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();
        }

        return $this->redirectToRoute('messages_index');
    }

    /**
     * Creates a form to delete a message entity.
     *
     * @param Messages $message The message entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Messages $message)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('messages_delete', array('id' => $message->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
