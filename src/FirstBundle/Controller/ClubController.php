<?php

namespace DorraBundle\Controller;

use AppBundle\Entity\Activite;
use AppBundle\Entity\Club;
use AppBundle\Entity\Jardin;
use DorraBundle\Form\ClubType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Club controller.
 *
 * @Route("club")
 */
class ClubController extends Controller
{
    /**
     * Lists all club entities.
     *
     * @Route("/back/", name="club_indexs", methods={"GET","POST"})

     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $clubs = $em->getRepository(Club::class)->findBy(array('jardin'=>$user->getJardin()));

        if($request->isMethod("post"))
        {

            $clubs=$em->getRepository(Club::class)->RechercheClub($request->get('search'),$request->get('tri'));
        }

        return $this->render('@Dorra/club/index.html.twig', array(
            'clubs' => $clubs,
        ));


    }

    /**
     * Lists all club entities.
     *
     * @Route("/parent/", name="club_index")
     * @Method("GET")
     */
    public function indexParentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $enfant= $user->getEnfants()[0];

        $clubs = $em->getRepository(Club::class)->findBy(array('jardin'=>$enfant->getAbonnements()[0]->getJardin()));

        if($request->isMethod("post"))
        {

            $clubs=$em->getRepository(Club::class)->RechercheClub($request->get('search'));
        }

        return $this->render('@Dorra/club/indexparent.html.twig', array(
            'clubs' => $clubs,
        ));


    }

    /**
     * Lister les activités d'un club précis
     *
     * @Route("/activiteclub/{id}", name="club_listeactivite")
     * @Method("GET")
     */
    public function activiteClubAction($id){

        $em = $this->getDoctrine()->getManager();

        $club = $this->getDoctrine()->getManager()->getRepository(Club::class)->find($id);
        $activites = $this->getDoctrine()->getManager()->getRepository(Activite::class)->findBy(array('club' => $id));

        return $this->render('@Dorra/club/listeactivite.html.twig', array(
            'activite' => $activites
        ));

    }
    /**
     * Creates a new club entity.
     *
     * @Route("/new", name="club_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $club->getPhoto();
            if($club->getPhoto()!=null)
            {
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                //$fileName = mdS(uniqid()).'.'.$file->queasExtintion();
                $file->move($this->getParameter('image_directory'),$fileName);
                $club->setPhoto($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();

            return $this->redirectToRoute('club_show', array('id' => $club->getId()));
        }

        return $this->render('@Dorra/club/new.html.twig', array(
            'club' => $club,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a club entity.
     *
     * @Route("/{id}", name="club_show")
     * @Method("GET")
     */
    public function showAction(Club $club)
    {
        $deleteForm = $this->createDeleteForm($club);

        return $this->render('@Dorra/club/show.html.twig', array(
            'club' => $club,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a club entity.
     *
     * @Route("/parent/{id}", name="club_showparent")
     * @Method("GET")
     */
    public function showparentAction(Club $club)
    {
        $deleteForm = $this->createDeleteForm($club);

        return $this->render('@Dorra/club/showparent.html.twig', array(
            'club' => $club,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing club entity.
     *
     * @Route("/{id}/edit", name="club_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Club $club)
    {
        $deleteForm = $this->createDeleteForm($club);
        $editForm = $this->createForm(ClubType::class, $club);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() ) {
            $file = $club->getPhoto();
            if($club->getPhoto()!=null)
            {
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                //$fileName = mdS(uniqid()).'.'.$file->queasExtintion();
                $file->move($this->getParameter('image_directory'),$fileName);
                $club->setPhoto($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $club->setJardin($em->getRepository(Jardin::class)->find(1 ));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('club_show', array('id' => $club->getId()));
        }

        return $this->render('@Dorra/club/edit.html.twig', array(
            'club' => $club,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a club entity.
     *
     * @Route("/delete/{id}", name="club_delete" )
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Club $club)
    {
        $form = $this->createDeleteForm($club);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($club);
            $em->flush();
        }

        return $this->redirectToRoute('club_index');
    }

    /**
     * Creates a form to delete a club entity.
     *
     * @param Club $club The club entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Club $club)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('club_delete', array('id' => $club->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    private function generateUniqueFileName()
    {

        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
