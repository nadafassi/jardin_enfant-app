<?php

namespace KarimBundle\Controller;


use AppBundle\Entity\Reclamation;
use KarimBundle\Form\ReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Reclamation controller.
 *
 * @Route("reclamation")
 */
class ReclamationController extends Controller
{
    /**
     * Lists all reclamation entities.
     *
     * @Route("/", name="reclamation_index",methods={"GET","HEAD"})
     */
    public function indexAction()
    {
        //this option is avaible for the admin on the desktop app so it s useless here
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $reclamations = $em->getRepository('AppBundle:Reclamation')->findmyreclam($user->getId());

        return $this->render('@Karim/reclamation/index.html.twig', array(
            'reclamations' => $reclamations,
        ));
    }

    /**
     * Creates a new reclamation entity.
     *
     * @Route("/new", name="reclamation_new")
     */
    public function newAction(Request $request)
    {
        //this action is for a user or a parent to send a claim

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
            $reclamation->setEtat("en attente");
            $em->persist($reclamation);
            $em->flush();

            return $this->redirectToRoute('reclamation_show', array('id' => $reclamation->getId()));
        }

        return $this->render('@Karim/reclamation/new.html.twig', array(
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a reclamation entity.
     *
     * @Route("/{id}", name="reclamation_show",methods={"GET","HEAD"})
     */
    public function showAction(Reclamation $reclamation)
    {
        //this action is useless too because it for the admin to check it on the desktop so no need to explain it
        $deleteForm = $this->createDeleteForm($reclamation);

        return $this->render('@Karim/reclamation/show.html.twig', array(
            'reclamation' => $reclamation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing reclamation entity.
     *
     * @Route("/{id}/edit", name="reclamation_edit",methods={"GET","POST"})
     */
    public function editAction(Request $request, Reclamation $reclamation)
    {
        //another useless action
        $deleteForm = $this->createDeleteForm($reclamation);
        $editForm = $this->createForm(ReclamationType::class, $reclamation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reclamation_show', array('id' => $reclamation->getId()));
        }

        return $this->render('@Karim/reclamation/edit.html.twig', array(
            'reclamation' => $reclamation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a reclamation entity.
     *
     * @Route("Delete/{id}", name="reclamation_delete",methods={"DELETE"})
     */
    public function deleteAction(Request $request, Reclamation $reclamation)
    {
        //probably we will need it later
        $form = $this->createDeleteForm($reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reclamation);
            $em->flush();
        }

        return $this->redirectToRoute('reclamation_index');
    }

    /**
     * Creates a form to delete a reclamation entity.
     *
     * @param Reclamation $reclamation The reclamation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Reclamation $reclamation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reclamation_delete', array('id' => $reclamation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
