<?php

namespace SamiBundle\Controller;

use AppBundle\Entity\Chauffeur;
use AppBundle\Entity\Enfant;
use AppBundle\Entity\Jardin;
use AppBundle\Entity\Trajet;
use SamiBundle\Form\TrajetType;
use SamiBundle\Models\MapModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


/**
 * Trajet controller.
 *
 * @Route("trajet")
 */
class TrajetController extends Controller
{
    /**
     * @Route(
     *      "/mapParent/",
     *      name="getMapParent"
     * )
     * @Method("GET")
     */
    public function mapParent()
    {
        $liste_trajets= array();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $enfant=new Enfant();
        $enfant=$user->getEnfants()[0];
        $id=$enfant->getAbonnements()[0]->getJardin();
        $liste=$this->getDoctrine()->getManager()->getRepository(Chauffeur::class)->findBy(array('jardin'=>$id));

        foreach ($liste as $ls)
        {
            foreach ($ls->getTrajet() as $l)
                array_push($liste_trajets,$l);

        }

        $finallist=array();
        foreach ($liste_trajets as $ls)
        {
            $json = file_get_contents('https://geocoder.ls.hereapi.com/6.2/geocode.json?searchtext='.$ls->getAdresse().'&gen=9&apiKey=CxxCHigH6e2itFdUuYEJdiNCKYOFT2wwtIF2QxxIjiw');
            $obj = json_decode($json);
            $map = new MapModel();
            $map->setLatitude($obj->Response->View[0]->Result[0]->Location->DisplayPosition->Latitude);
            $map->setLongitude($obj->Response->View[0]->Result[0]->Location->DisplayPosition->Longitude);
            $map->setAdresse($ls->getAdresse());
            $map->setHeure($ls->getHeure());
            $map->setChauffeur($ls->getChauffeur()->getNom());
            array_push($finallist, $map);

        }

        $serializer = new Serializer([new ObjectNormalizer()]);

        $dataJson = $serializer->normalize($finallist);

        return new JsonResponse($dataJson);

    }
    /**
     * Lists all trajet entities.
     *
     * @Route("/trajetParent", name="trajet_map")
     * @Method("GET")
     */
    public function parentAction()
    {
        $liste_trajets= array();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $enfant=new Enfant();
        $enfant=$user->getEnfants()[0];
        $jardin=$enfant->getAbonnements()[0]->getJardin();
        return $this->render('@Sami/trajet/mapParent.html.twig', array(
            'jardin' => $jardin,
        ));
    }
    /**
     * Lists all trajet entities.
     *
     * @Route("/", name="trajet_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $trajets = $em->getRepository('AppBundle:Trajet')->findAll();

        return $this->render('@Sami/trajet/index.html.twig', array(
            'trajets' => $trajets,
        ));
    }
    /**
     * @Route(
     *      "/map/",
     *      name="getMap"
     * )
     * @Method("GET")
     */
  public function mapJson()
  {
      $liste_trajets= array();
      $user = $this->container->get('security.token_storage')->getToken()->getUser();
      $liste=$this->getDoctrine()->getManager()->getRepository(Chauffeur::class)->findBy(array('jardin'=>$user->getJardin()));



      foreach ($liste as $ls)
      {
        foreach ($ls->getTrajet() as $l)
          array_push($liste_trajets,$l);

      }

      $finallist=array();
      foreach ($liste_trajets as $ls)
      {
          $json = file_get_contents('https://geocoder.ls.hereapi.com/6.2/geocode.json?searchtext='.$ls->getAdresse().'&gen=9&apiKey=CxxCHigH6e2itFdUuYEJdiNCKYOFT2wwtIF2QxxIjiw');
          $obj = json_decode($json);
          $map = new MapModel();
          $map->setLatitude($obj->Response->View[0]->Result[0]->Location->DisplayPosition->Latitude);
          $map->setLongitude($obj->Response->View[0]->Result[0]->Location->DisplayPosition->Longitude);
          $map->setAdresse($ls->getAdresse());
          $map->setHeure($ls->getHeure());
          $map->setChauffeur($ls->getChauffeur()->getNom());
          array_push($finallist, $map);

      }

      $serializer = new Serializer([new ObjectNormalizer()]);

      $dataJson = $serializer->normalize($finallist);

      return new JsonResponse($dataJson);
  }
    /**
     * Creates a new trajet entity.
     *
     * @Route("/new", name="trajet_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $trajet = new Trajet();
        $form = $this->createForm(TrajetType::class, $trajet,array('user'=>$user->getJardin()));
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($trajet);
            $em->flush();

            return $this->redirectToRoute('trajet_show', array('id' => $trajet->getId()));
        }

        return $this->render('@Sami/trajet/new.html.twig', array(
            'trajet' => $trajet,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a trajet entity.
     *
     * @Route("/{id}", name="trajet_show")
     * @Method("GET")
     */
    public function showAction(Trajet $trajet)
    {
        $deleteForm = $this->createDeleteForm($trajet);

        return $this->render('@Sami/trajet/show.html.twig', array(
            'trajet' => $trajet,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing trajet entity.
     *
     * @Route("/{id}/edit", name="trajet_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Trajet $trajet)
    {
        $deleteForm = $this->createDeleteForm($trajet);
        $editForm = $this->createForm('SamiBundle\Form\TrajetType', $trajet);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trajet_edit', array('id' => $trajet->getId()));
        }

        return $this->render('@Sami/trajet/edit.html.twig', array(
            'trajet' => $trajet,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a trajet entity.
     *
     * @Route("/delete/{id}", name="trajet_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Trajet $trajet)
    {
        $form = $this->createDeleteForm($trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($trajet);
            $em->flush();
        }

        return $this->redirectToRoute('trajet_index');
    }

    /**
     * Creates a form to delete a trajet entity.
     *
     * @param Trajet $trajet The trajet entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Trajet $trajet)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('trajet_delete', array('id' => $trajet->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
