<?php

namespace FeridBundle\Controller;

use AppBundle\Entity\Abonnement;
use AppBundle\Entity\Jardin;
use AppBundle\Entity\Parents;
use FeridBundle\Form\AbonnementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Abonnement controller.
 *
 * @Route("abonnement")
 */
class AbonnementController extends Controller
{
    /**
     * Lists all abonnement entities.
     *
     * @Route("/index", name="abonnement_index",methods={"GET","POST"})
     *
     */
    public function indexAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $enfants = $em->getRepository('AppBundle:Enfant')->findBy(array('parent'=>$user));
        $abonnements = $em->getRepository('AppBundle:Abonnement')->findBy(array('enfant'=>$enfants));
        if($request->isMethod("post"))
        {

            $abonnements=$em->getRepository(Abonnement::class)->searchAbonnemParent($request->get('search'),$user,$request->get('tri'));
        }


        return $this->render('@Ferid/abonnement/index.html.twig', array(
            'abonnements' => $abonnements,
        ));
    }


    /**
     * Lists all abonnement entities.
     *
     * @Route("/resp/index", name="abonnements_index",methods={"GET","POST"})
     *
     */
    public function indexaboAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $abonnements = $em->getRepository(Abonnement::class)->findBy(array('jardin'=>$user->getJardin()));
        if($request->isMethod("post"))
        {

            $abonnements=$em->getRepository(Abonnement::class)->searchAbonnements($request->get('search'),$user->getJardin(),$request->get('tris'));
        }

        return $this->render('@Ferid/abonnement/indexback.html.twig', array(
            'abonnements' => $abonnements,
        ));
    }

    /**
     *
     * @Route("/facture/{id}",name="facture_abon",methods={"GET"})
     *
     */
    public function factureAction(Abonnement $abonnement)
    {
        $snappy = $this->get("knp_snappy.pdf");
        $html = $this->renderView("@Ferid/abonnement/facture.html.twig", array('abonnement' => $abonnement,'title' => 'Abonnement Enfant',));
        $filname = "custom_pdf_form_twig";
        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filname . '.pdf"'
            )
        );

    }

    /**
     *
     * @Route("/pdf/{id}",name="getpdf",methods={"GET","HEAD"})

     *
     */
    public function pdfAction(Abonnement $abonnement)
    {
        $snappy = $this->get("knp_snappy.pdf");
        $html = $this->renderView("@Ferid/abonnement/pdf.html.twig", array('abonnement' => $abonnement,'title' => 'Abonnement Enfant',));
        $filname = "custom_pdf_form_twig";
        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filname . '.pdf"'
            )
        );

    }
    /**
     * Creates a new abonnement entity.
     *
     * @Route("/new", name="abonnement_new",methods={"GET", "POST"})

     */
    public function newAction(Request $request,Jardin $jardin)
    {
        $abonnement = new Abonnement();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(AbonnementType::class, $abonnement,array('user'=>$user->getId()));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $time=new \DateTime('now');
            $abonnement->setDate($time);
            $abonnement->setJardin($jardin);
            $abonnement->setMontant($jardin->getTarif());

            $em = $this->getDoctrine()->getManager();
            $em->persist($abonnement);
            $em->flush();

            return $this->redirectToRoute('facture_abon', array('id' => $abonnement->getId()));
        }

        return $this->render('@Ferid/abonnement/new.html.twig', array(
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ));
    }


    /**
     * Creates a new abonnement entity.
     *
     * @Route("/{id}", name="abonnement_news",methods={"GET", "POST"})

     */
    public function newsAction(Request $request,Jardin $jardin)
    {
        $abonnement = new Abonnement();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(AbonnementType::class, $abonnement,array('user'=>$user->getId()));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $time=new \DateTime('now');
            $abonnement->setDate($time);
            $abonnement->setJardin($jardin);
            $abonnement->setMontant($jardin->getTarif());

            $em = $this->getDoctrine()->getManager();
            $em->persist($abonnement);
            $em->flush();

            return $this->redirectToRoute('facture_abon', array('id' => $abonnement->getId()));
        }

        return $this->render('@Ferid/abonnement/new.html.twig', array(
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a abonnement entity.
     *
     * @Route("/{id}/show", name="abonnement_show",methods={"GET","HEAD"})

     */
    public function showAction(Abonnement $abonnement)
    {
        $deleteForm = $this->createDeleteForm($abonnement);

        return $this->render('@Ferid/abonnement/show.html.twig', array(
            'abonnement' => $abonnement,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Finds and displays a abonnement entity.
     *
     * @Route("/resp/{id}", name="abonnements_show",methods={"GET","HEAD"})

     */
    public function showbackAction(Abonnement $abonnement)
    {
        $deleteForm = $this->createDeleteForm($abonnement);

        return $this->render('@Ferid/abonnement/showback.html.twig', array(
            'abonnement' => $abonnement,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing abonnement entity.
     *
     * @Route("/{id}/edit", name="abonnement_edit",methods={"GET", "POST"})

     */
    public function editAction(Request $request, Abonnement $abonnement)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $deleteForm = $this->createDeleteForm($abonnement);
        $editForm = $this->createForm(AbonnementType::class, $abonnement,array('user'=>$user->getId()));

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() ) {
            $time=new \DateTime('now');
            $abonnement->setDate($time);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('abonnement_show', array('id' => $abonnement->getId()));
        }

        return $this->render('@Ferid/abonnement/edit.html.twig', array(
            'abonnement' => $abonnement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing abonnement entity.
     *
     * @Route("/{id}/accepter", name="abonnement_accepter",methods={"GET", "POST"})

     */
    public function accepterAction(Abonnement $abonnement)
    {

        $abonn=$this->getDoctrine()->getManager()->getRepository(Abonnement::class)->find($abonnement->getId());
        $abonn->setEtat("accepté");
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('abonnements_index', array('id' => $abonnement->getId()));



    }


    /**
     * Deletes a abonnement entity.
     *
     * @Route("/{id}", name="abonnement_delete",methods={"DELETE"})

     */
    public function deleteAction(Request $request, Abonnement $abonnement)
    {
        $form = $this->createDeleteForm($abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($abonnement);
            $em->flush();
        }

        return $this->redirectToRoute('abonnement_index');
    }





    /**
     * Creates a form to delete a abonnement entity.
     *
     * @param Abonnement $abonnement The abonnement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Abonnement $abonnement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('abonnement_delete', array('id' => $abonnement->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
