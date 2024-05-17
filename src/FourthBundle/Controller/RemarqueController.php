<?php

namespace KarimBundle\Controller;

use AppBundle\Entity\Remarque;
use KarimBundle\Form\RemarqueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Remarque controller.
 *
 * @\Symfony\Component\Routing\Annotation\Route("remarque")
 */
class RemarqueController extends Controller
{
    /**
     * Lists all remarque entities.
     *
     * @Route("/", name="remarque_index",methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        //this action is so important because it containe filter, pagination and sorting using knp paginator
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

       // $dql="select a from AppBundle:Remarque a";
        $qb=$em->createQueryBuilder('a')->select("a")->from("AppBundle:Remarque","a")->join('a.abonnement','ab')->join('ab.enfant','en')->join("en.parent",'p');
        if($request->query->getAlnum("filter")){
            $qb=$qb->where('p.id=:id and (en.nom like :filter or a.description like :filter)')
                ->setParameter('filter', '%' . $request->query->getAlnum('filter') . '%')->setParameter("id",$user->getId());
        }else{
            $qb=$qb->where('p.id=:id')->setParameter("id",$user->getId());
        }
        $remarques = $qb->getQuery();

        $paginator  = $this->get('knp_paginator');


        $rq = $paginator->paginate(
            $remarques,
           $request->query->get('page',1) /*page number*/,
            $request->query->get('limit',5) /*limit per page*/
        );


        return $this->render('@Karim/remarque/index.html.twig', array(
            'remarques' => $rq,
        ));
    }

    /**
     * Creates a new remarque entity.
     *
     * @Route("/new", name="remarque_new",methods={"GET","POST"})
     */
    public function newAction(Request $request)
    {
        //this option is avaible for the tutor in the mobile app to use it
        $remarque = new Remarque();
        $form = $this->createForm(RemarqueType::class, $remarque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($remarque);
            $em->flush();

            return $this->redirectToRoute('remarque_show', array('id' => $remarque->getId()));
        }

        return $this->render('@Karim/remarque/new.html.twig', array(
            'remarque' => $remarque,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a remarque entity.
     *
     * @Route("/{id}", name="remarque_show",methods={"GET"})
     */
    public function showAction(Remarque $remarque)
    {
        //i m trying to figure out how to replace it with modal
        $deleteForm = $this->createDeleteForm($remarque);

        return $this->render('@Karim/remarque/show.html.twig', array(
            'remarque' => $remarque,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing remarque entity.
     *
     * @Route("/{id}/edit", name="remarque_edit",methods={"GET", "POST"})
     */
    public function editAction(Request $request, Remarque $remarque)
    {
        //useless too for now maybe later we will change the idea
        $deleteForm = $this->createDeleteForm($remarque);
        $editForm = $this->createForm(RemarqueType::class, $remarque);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('remarque_edit', array('id' => $remarque->getId()));
        }

        return $this->render('@Karim/remarque/edit.html.twig', array(
            'remarque' => $remarque,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a remarque entity.
     *
     * @Route("/{id}", name="remarque_delete",methods={"DELETE"})
     */
    public function deleteAction(Request $request, Remarque $remarque)
    {
        //maybe useless i can neither approve nor deny that facr
        $form = $this->createDeleteForm($remarque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($remarque);
            $em->flush();
        }

        return $this->redirectToRoute('remarque_index');
    }

    /**
     * Creates a form to delete a remarque entity.
     *
     * @param Remarque $remarque The remarque entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Remarque $remarque)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('remarque_delete', array('id' => $remarque->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
