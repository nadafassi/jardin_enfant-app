<?php

namespace DorraBundle\Controller;

use AppBundle\Entity\Activite;
use AppBundle\Entity\Club;
use AppBundle\Entity\PartActivite;
use DorraBundle\Form\PartActiviteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Activite controller.
 *
 * @Route("activite")
 */
class ActiviteController extends Controller
{
    /**
     * Lists all activite entities.
     *
     * @Route("/backactivite/", name="activite_indexback" , methods={"GET","POST"})
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $activites = $em->getRepository('AppBundle:Activite')->findAll();

        if($request->isMethod("post"))
        {

            $activites=$em->getRepository(Activite::class)->RechercheActivite($request->get('search'),$request->get('tri'));
        }


        return $this->render('@Dorra/activite/index.html.twig', array(
            'activites' => $activites,
        ));
    }
    /**
     * Lists all activite entities.
     *
     * @Route("/parentactivite/", name="activite_index")
     * @Method("GET")
     */
    public function indexparentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $activites = $em->getRepository('AppBundle:Activite')->findAll();

        if($request->isMethod("post"))
        {

            $activites=$em->getRepository(Activite::class)->RechercheActivite($request->get('search'));
        }


        return $this->render('@Dorra/activite/indexparent.html.twig', array(
            'activites' => $activites,
        ));
    }

    /**
     * Creates a new activite entity.
     *
     * @Route("/new", name="activite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $activite = new Activite('', New \DateTime('now')
        );
        $form = $this->createForm('DorraBundle\Form\ActiviteType', $activite);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $file = $activite->getPhoto();
            if ($activite->getPhoto() != null) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                //$fileName = mdS(uniqid()).'.'.$file->queasExtintion();
                $file->move($this->getParameter('image_directory'), $fileName);
                $activite->setPhoto($fileName);
            }


            $em = $this->getDoctrine()->getManager();
            $activite->setClub($em->getRepository(Club::class)->find(1));
            $activite->setDateDebut(New \DateTime('now'));
            $activite->setDateFin(New \DateTime('now'));
            $activite->setDateCreation(New \DateTime('now'));
            $em->persist($activite);
            $em->flush();

            return $this->redirectToRoute('activite_show', array('id' => $activite->getId()));
        }

        return $this->render('@Dorra/activite/new.html.twig', array(
            'activite' => $activite,
            'form' => $form->createView(),
        ));
    }


    /**
     * calendrier
     *
     * @Route("/calendrier",name="activite_calendrier")
     * @Method("GET")
     */
    public function AfficherCalendrier()
    {


        return $this->render('@Dorra/activite/calendrier.html.twig', array(''));
    }

    /**
     * calendrier
     *
     * @Route("/calendrierModify",name="date")
     * @Method("GET")
     */
    public function modifyAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $titre = $request->get('event');
        $start = $request->get('datedebut');
        $end = $request->get('datefin');
        $user = $request->get('user');
        $evenement = $em->getRepository(Activite::class)->findOneBy(array("typeact" => $titre));
        if ($user != $evenement->getResponsable()->getId()) {
            return new Response("no");
        }
        $evenement->setDateDebut(new \DateTime($start));
        $evenement->setDateFin(new \DateTime($end));
        $em->merge($evenement);
        $em->flush();
        return new Response("yes");
    }


    /**
     * Finds and displays a activite entity.
     *
     * @Route("/{id}", name="activite_show")
     * @Method("GET")
     */
    public function showAction(Activite $activite)
    {
        $deleteForm = $this->createDeleteForm($activite);

        return $this->render('@Dorra/activite/show.html.twig', array(
            'activite' => $activite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a activite entity.
     *
     * @Route("/showparent/{id}", name="activite_showparent")
     * @Method("GET")
     */
    public function showparentAction(Activite $activite)
    {
        $deleteForm = $this->createDeleteForm($activite);

        return $this->render('@Dorra/activite/showparent.html.twig', array(
            'activite' => $activite,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing activite entity.
     *
     * @Route("/{id}/edit", name="activite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Activite $activite)
    {
        $deleteForm = $this->createDeleteForm($activite);
        $editForm = $this->createForm('DorraBundle\Form\ActiviteType', $activite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {

            $file = $activite->getPhoto();
            if ($activite->getPhoto() != null) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                //$fileName = mdS(uniqid()).'.'.$file->queasExtintion();
                $file->move($this->getParameter('image_directory'), $fileName);
                $activite->setPhoto($fileName);
            }

            $em = $this->getDoctrine()->getManager();

            //$activite->setClub($em->getRepository(Club::class)->find(1 ));
            $em->merge($activite);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activite_show', array('id' => $activite->getId()));
        }

        return $this->render('@Dorra/activite/edit.html.twig', array(
            'activite' => $activite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * participer à une activité
     *
     * @Route("/participer/{id}", name="activite_part")
     * @Method({"GET", "POST"})
     */
    public function ParticiperAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $activite = $this->getDoctrine()->getManager()->getRepository(Activite::class)->find($id);
        $participe = new PartActivite();
        $form = $this->createForm(PartActiviteType::class, $participe, array('user' => $user));
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $participe->setDate(New \DateTime('now'));
            $participe->setActivite($activite);
            $em = $this->getDoctrine()->getManager();
            $em->persist($participe);

            $em->flush();


            return $this->render('@Dorra/activite/participer.html.twig', array('activites' => $activite,
                'form' => $form->createView(),
                'msg' => 'participation est confirmée'));
        }

        return $this->render('@Dorra/activite/participer.html.twig', array(
            'activites' => $activite,
            'form' => $form->createView(),
            'msg' => ''
        ));
    }

    /**
     * liste des participants à l'activité
     *
     * @Route("/liste/{id}", name="activite_liste")
     * @Method("GET")
     */
    public function afficherParticipantAction($id)
    {


        $em = $this->getDoctrine()->getManager();

        $activite = $this->getDoctrine()->getManager()->getRepository(Activite::class)->find($id);
        $enfants = $this->getDoctrine()->getManager()->getRepository(PartActivite::class)->findBy(array('Activite' => $id));

        return $this->render('@Dorra/activite/listeparticipant.html.twig', array(
            'enfants' => $enfants
        ));

    }

    /**
     * Deletes a activite entity.
     *
     * @Route("/delete/{id}", name="activite_delete", methods="DELETE")
     */
    public function deleteAction(Request $request, Activite $activite)
    {
        $form = $this->createDeleteForm($activite);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($activite);
            $em->flush();
        }

        return $this->redirectToRoute('activite_indexs');
    }

    /**
     * Creates a form to delete a activite entity.
     *
     * @param Activite $activite The activite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Activite $activite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('activite_delete', array('id' => $activite->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function generateUniqueFileName()
    {

        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
