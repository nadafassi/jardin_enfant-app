<?php

namespace RaedBundle\Controller;

use AppBundle\Entity\Chauffeur;
use AppBundle\Entity\Jardin;
use AppBundle\Entity\Responsable;
use AppBundle\Form\ResponsableType;
use Knp\Component\Pager\PaginatorInterface;
use RaedBundle\Form\jardinType;
use RaedBundle\models\jardinmodels;
use SamiBundle\Models\MapModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Jardin controller.
 *
 * @Route("jardin")
 */
class jardinController extends Controller
{
    /**
     * Lists all jardin entities.
     *
     * @Route("/", name="jardin_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $jardins = $em->getRepository('AppBundle:Jardin')->findAll();

        if($request->isMethod("post"))
        {

            $jardins=$em->getRepository(Jardin::class)->searchJardins($request->get('search'));
        }


        $qb=$em->createQueryBuilder('a')->select("a")->from("AppBundle:Jardin","a");
        if($request->query->getAlnum("filter")){
            $qb=$qb
                ->where('a.name like :filter or a.description like :filter or a.adress like :filter ')
                ->setParameter('filter', '%' . $request->query->getAlnum('filter') . '%');
        }
        $remarques = $qb->getQuery();



        $paginator  = $this->get('knp_paginator');


        $rq = $paginator->paginate(
            $jardins,
            $request->query->get('page',1) /*page number*/,
            $request->query->get('limit',2) /*limit per page*/
        );


        return $this->render('@Raed/jardin/index.html.twig', array(
            'jardins' => $rq,
        ));

    }



    /**
     * Creates a new jardin entity.
     *
     * @Route("/new", name="jardin_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {



        $resp=new Responsable();
        $jardin = new Jardin();
        $form = $this->createForm(jardinType::class, $jardin);
       $form1=$this->createForm(ResponsableType::class,$resp);
        $form->handleRequest($request);
        $form1->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $resp->addRole("ROLE_RESPONSABLE");
            $resp->setEnabled(true);
            $jardin->setResponsable($resp);
            $resp->setJardin($jardin);
            $jardin->setEtat("En ATTENTE");
            $em = $this->getDoctrine()->getManager();
            $em->persist($jardin);
            $em->persist($resp);
            $em->flush();

            return $this->redirectToRoute('jardin_show', array('id' => $jardin->getId()));
        }

        return $this->render('@Raed/jardin/new.html.twig', array(
            'jardin' => $jardin,
            'form' => $form->createView(),
            'form1'=>$form1->createView(),
            'responsable'=>$resp
        ));
    }

    /**
     * Finds and displays a jardin entity.
     *
     * @Route("/{id}", name="jardin_show")
     * @Method("GET")
     */
    public function showAction(jardin $jardin)
    {
        $deleteForm = $this->createDeleteForm($jardin);

        return $this->render('@Raed/jardin/show.html.twig', array(
            'jardin' => $jardin,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing jardin entity.
     *
     * @Route("/{id}/edit", name="jardin_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, jardin $jardin)
    {
        $deleteForm = $this->createDeleteForm($jardin);
        $editForm = $this->createForm(jardinType::class, $jardin);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jardin_edit', array('id' => $jardin->getId()));
        }

        return $this->render('@Raed/jardin/edit.html.twig', array(
            'jardin' => $jardin,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a jardin entity.
     *
     * @Route("/{id}", name="jardin_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, jardin $jardin)
    {
        $form = $this->createDeleteForm($jardin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($jardin);
            $em->flush();
        }

        return $this->redirectToRoute('jardin_index');
    }

    /**
     * Creates a form to delete a jardin entity.
     *
     * @param jardin $jardin The jardin entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(jardin $jardin)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('jardin_delete', array('id' => $jardin->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
