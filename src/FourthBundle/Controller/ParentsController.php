<?php

namespace KarimBundle\Controller;

use AppBundle\Entity\Parents;
use KarimBundle\Form\ParentsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Parent controller.
 *
 * @Route("parents")
 */
class ParentsController extends Controller
{

    /**
     * login
     *
     * @Route("/login", name="parents_login")
     * @Method({"GET","POST"})
     */
    public function loginAction(Request $request)
    {
        //login action don't ask me how it woks because i don't know XD
        $username=$request->get('username');
        $password=$request->get('password');

        if($request->isMethod("GET")){
            return $this->render("@Karim/parents/login.html.twig",array("msg"=>""));
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
             return $this->render("@Karim/parents/login.html.twig",array("msg"=>"user does not exist"));
            }

        /// Start verification
        $encoder = $factory->getEncoder($user);
        $salt = $user->getSalt();

        if(!$encoder->isPasswordValid($user->getPassword(), $password, $salt)) {
            return $this->render("@Karim/parents/login.html.twig",array("msg"=>"username or password are incorrect"));
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

        /*
         * Now the user is authenticated !!!!
         * Do what you need to do now, like render a view, redirect to route etc.
         */
        return $this->redirectToRoute("homepage");
        }
    }





    /**
     * Lists all parent entities.
     *
     * @Route("/", name="parents_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        //maybe usefull for reponsable jardin and the admin
        $em = $this->getDoctrine()->getManager();

        $parents = $em->getRepository('AppBundle:Parents')->findAll();

        return $this->render('@Karim/parents/index.html.twig', array(
            'parents' => $parents,
        ));
    }

    /**
     * Creates a new parent entity.
     *
     * @Route("/new", name="parents_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        //signup action for the parent good one
        $parent = new Parents();
        $form = $this->createForm(ParentsType::class, $parent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $parent->addRole("ROLE_Parent");
            $parent->setEnabled(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($parent);
            $em->flush();

            return $this->redirectToRoute('parents_show', array('id' => $parent->getId()));
        }

        return $this->render('@Karim/parents/new.html.twig', array(
            'parent' => $parent,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a parent entity.
     *
     * @Route("/{id}", name="parents_show")
     * @Method("GET")
     */
    public function showAction(Parents $parent)
    {
        //useless
        $deleteForm = $this->createDeleteForm($parent);

        return $this->render('@Karim/parents/show.html.twig', array(
            'parent' => $parent,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing parent entity.
     *
     * @Route("/{id}/edit", name="parents_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Parents $parent)
    {
        //i need it for the parent to update his profile but it must be enhanced
        $deleteForm = $this->createDeleteForm($parent);
        $editForm = $this->createForm(ParentsType::class, $parent);
        $editForm->remove("plainPassword");
        $editForm->handleRequest($request);
        $oldpass=$request->get('old');
        $newpass=$request->get("new");
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($parent);

        if(!strcmp($oldpass,$newpass)==0){
            return $this->render('@Karim/parents/edit.html.twig', array(
                'parent' => $parent,
                'form' => $editForm->createView(),
              'msg'=>"les mots de passe ne correspondent pas "
            ));
        }else if($oldpass!=null){
            $parent->setPlainPassword($oldpass);
        }


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($parent);



            return $this->redirect('/');
        }

        return $this->render('@Karim/parents/edit.html.twig', array(
            'parent' => $parent,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a parent entity.
     *
     * @Route("/{id}", name="parents_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Parents $parent)
    {
        //idon't know if i need it
        $form = $this->createDeleteForm($parent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($parent);
            $em->flush();
        }

        return $this->redirectToRoute('parents_index');
    }

    /**
     * Creates a form to delete a parent entity.
     *
     * @param Parents $parent The parent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Parents $parent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parents_delete', array('id' => $parent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
