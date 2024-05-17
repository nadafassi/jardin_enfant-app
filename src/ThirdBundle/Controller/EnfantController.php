<?php

namespace FeridBundle\Controller;

use AppBundle\Entity\Abonnement;
use AppBundle\Entity\Enfant;
use AppBundle\Entity\Parents;
use AppBundle\Entity\User;
use FeridBundle\Form\EnfantType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Enfant controller.
 *
 * @Route("enfant")
 */
class EnfantController extends Controller
{
    /**
     * Lists all enfant entities.
     *
     * @Route("/index", name="enfant_index",methods={"GET","POST"})

     */
    public function indexAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();


        $em = $this->getDoctrine()->getManager();




        // $dql="select a from AppBundle:Remarque a";
        $qb=$em->createQueryBuilder('a')->select("a")->from("AppBundle:Enfant","a")
            ->where("a.parent=:parent")->setParameter("parent",$user);
        if($request->query->getAlnum("filter")){
            $qb=$qb
                ->AndWhere('a.nom like :filter or a.prenom like :filter or a.sexe like :filter')
                ->setParameter('filter', '%' . $request->query->getAlnum('filter') . '%');
        }
        $enfants = $qb->getQuery();


        $paginator  = $this->get('knp_paginator');


        $rq = $paginator->paginate(
            $enfants,
            $request->query->get('page',1) /*page number*/,
            $request->query->get('limit',100) /*limit per page*/
        );
        if($request->isMethod("post"))
        {

            $rq=$em->getRepository(Enfant::class)->searchEnfant($request->get('search'),$user);
        }

        return $this->render('@Ferid/enfant/index.html.twig', array(
            'enfants' => $rq,
        ));
    }

    /**
     * Creates a new enfant entity.
     *
     * @Route("/new", name="enfant_new",methods={"GET", "POST"})

     */
    public function newAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $enfant = new Enfant();
        $form = $this->createForm(EnfantType::class, $enfant);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $enfant->setParent($this->getDoctrine()->getManager()->getRepository(Parents::class)->find($user->getId()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($enfant);
            $em->flush();

            return $this->redirectToRoute('enfant_show', array('id' => $enfant->getId()));
        }


        return $this->render('@Ferid/enfant/new.html.twig', array(
            'enfant' => $enfant,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a enfant entity.
     *
     * @Route("/{id}", name="enfant_show",methods={"GET", "POST"})

     */
    public function showAction(Enfant $enfant)
    {
        $deleteForm = $this->createDeleteForm($enfant);

        return $this->render('@Ferid/enfant/show.html.twig', array(
            'enfant' => $enfant,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing enfant entity.
     *
     * @Route("/{id}/edit", name="enfant_edit",methods={"GET", "POST"})

     */
    public function editAction(Request $request, Enfant $enfant)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $deleteForm = $this->createDeleteForm($enfant);
        $editForm = $this->createForm(EnfantType::class, $enfant);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {
            $enfant->setParent($this->getDoctrine()->getManager()->getRepository(Parents::class)->find($user->getId()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('enfant_show', array('id' => $enfant->getId()));
        }

        return $this->render('@Ferid/enfant/edit.html.twig', array(
            'enfant' => $enfant,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a enfant entity.
     *
     * @Route("/delete/{id}", name="enfant_delete",methods={"DELETE"})

     */
    public function deleteAction(Request $request, Enfant $enfant)
    {
        $form = $this->createDeleteForm($enfant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($enfant);
            $em->flush();
        }

        return $this->redirectToRoute('enfant_index');
    }

    /**
     * Creates a form to delete a enfant entity.
     *
     * @param Enfant $enfant The enfant entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Enfant $enfant)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('enfant_delete', array('id' => $enfant->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
