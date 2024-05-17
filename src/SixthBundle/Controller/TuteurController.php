<?php

namespace SamiBundle\Controller;

use AppBundle\Entity\Tuteur;
use SamiBundle\Form\TuteurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Tuteur controller.
 *
 * @Route("tuteur")
 */
class TuteurController extends Controller
{
    /**
     * Lists all tuteur entities.
     *
     * @Route("/", name="tuteur_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $tuteurs = $em->getRepository(Tuteur::class)->findBy(array('jardin'=>$user->getJardin()));
        if($request->isMethod("post"))
        {

            $tuteurs=$em->getRepository(Tuteur::class)->searchTuteurs($request->get('search'),$user->getJardin(),$request->get('tri'));
        }
        return $this->render('@Sami/tuteur/index.html.twig', array(
            'tuteurs' => $tuteurs,
        ));
    }

    /**
     * Creates a new tuteur entity.
     *
     * @Route("/new", name="tuteur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $tuteur = new Tuteur();
        $form = $this->createForm(TuteurType::class, $tuteur);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $tuteur->addRole("ROLE_Tuteur");
            $tuteur->setJardin($user->getJardin());
            $em = $this->getDoctrine()->getManager();
            $em->persist($tuteur);
            $em->flush();

            return $this->redirectToRoute('tuteur_show', array('id' => $tuteur->getId()));
        }

        return $this->render('@Sami/tuteur/new.html.twig', array(
            'tuteur' => $tuteur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tuteur entity.
     *
     * @Route("/{id}", name="tuteur_show")
     * @Method("GET")
     */
    public function showAction(Tuteur $tuteur)
    {
        $deleteForm = $this->createDeleteForm($tuteur);

        return $this->render('@Sami/tuteur/show.html.twig', array(
            'tuteur' => $tuteur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tuteur entity.
     *
     * @Route("/{id}/edit", name="tuteur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tuteur $tuteur)
    {
        $deleteForm = $this->createDeleteForm($tuteur);
        $editForm = $this->createForm(TuteurType::class, $tuteur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tuteur_edit', array('id' => $tuteur->getId()));
        }

        return $this->render('@Sami/tuteur/edit.html.twig', array(
            'tuteur' => $tuteur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tuteur entity.
     *
     * @Route("/delete/{id}", name="tuteur_delete",methods={"DELETE"})

     */
    public function deleteAction(Request $request, Tuteur $tuteur)
    {
        $form = $this->createDeleteForm($tuteur);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tuteur);
            $em->flush();
        }

        return $this->redirectToRoute('tuteur_index');
    }

    /**
     * Creates a form to delete a tuteur entity.
     *
     * @param Tuteur $tuteur The tuteur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tuteur $tuteur)
    {
        return ($this->createFormBuilder()
            ->setAction($this->generateUrl('tuteur_delete', array('id' => $tuteur->getId())))
            ->setMethod('DELETE')
            ->getForm())  ;

    }
}
