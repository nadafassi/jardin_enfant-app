<?php

namespace SamiBundle\Controller;

use AppBundle\Entity\Chauffeur;
use AppBundle\Entity\Trajet;
use AppBundle\Entity\Tuteur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Zend\Json\Json;

/**
 * Chauffeur controller.
 *
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * Lists all parent entities.
     *
     * @Route("/listtrajets/{id}", name="trajetsparjardin_api")
     */
    public function allTrajetsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT t.id,t.heure,t.adresse,c.id as chauffeur_id
    FROM AppBundle:Trajet t,AppBundle:Chauffeur c WHERE t.chauffeur=c.id and c.jardin=:id'
        )
            ->setParameter('id',$id);

        $list = $query->getArrayResult();

        return new JsonResponse($list);
    }

    /**
     * Lists all parent entities.
     *
     * @Route("/listpar/{id}", name="trajets_api")
     */
    public function trajetsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT c
    FROM AppBundle:Trajet c WHERE c.chauffeur=:id'
        )
            ->setParameter('id',$id);

        $list = $query->getArrayResult();

        return new JsonResponse($list);
    }

    /**
     * Suppression d'un trajet.
     *
     * @Route("/deleteTrajet/{id}", name="trajets_supprimer")
     */
    public function supprimerTrajetAction($id)
    {
try
  {
    $em = $this->getDoctrine()->getManager();
    $em->remove($em->getRepository(Trajet::class)->find($id));
    $em->flush();
    return new JsonResponse(true);
  }
  catch (\Exception $exception)
  {
    return new JsonResponse(false);
  }

    }

    /**
     * ajout d'un trajet.
     *
     * @Route("/addTrajet", name="trajets_ajouter")
     */
    public function addTrajet(Request $request)
    {
        try{
    $chauff=$this->getDoctrine()->getManager()->getRepository(Chauffeur::class)->find($request->get('id'));
    $trajet=new Trajet();
$trajet->setHeure($request->get('heure'));
$trajet->setAdresse($request->get('adresse'));
$trajet->setChauffeur($chauff);
$this->getDoctrine()->getManager()->persist($trajet);
$this->getDoctrine()->getManager()->flush();

        return new JsonResponse(true);
    }catch (\Exception $exception)
        {
            return new JsonResponse(false);
        }
    }

    /**
     * Lists all parent entities.
     *
     * @Route("/chauffeurs/{id}", name="chauffeurs_api")
     */
    public function chauffeursAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT c
    FROM AppBundle:Chauffeur c WHERE c.jardin=:id'
        )->setParameter('id',$id);

        $list = $query->getArrayResult();

        return new JsonResponse($list);
    }
    /**
     *
     * @Route("/getchauffeur/{id}", name="getchauff_api")
     */
    public function getChauffeurAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT c
    FROM AppBundle:Chauffeur c WHERE c.id=:id'
        )->setParameter('id',$id);

        $list = $query->getArrayResult();

        return new JsonResponse($list[0]);
    }
    /**
     *
     * @Route("/gettuteur/{id}", name="gettut_api")
     */
    public function getTuteurAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT c
    FROM AppBundle:Tuteur c WHERE c.id=:id'
        )->setParameter('id',$id);

        $list = $query->getArrayResult();

        return new JsonResponse($list[0]);
    }

    /**
     * ajout d'un trajet.
     *
     * @Route("/modifierChauffeur", name="chauffeur_modifier")
     */
    public function modifierChauffeur(Request $request)
    {
        try{
            $chauff=$this->getDoctrine()->getManager()->getRepository(Chauffeur::class)->find($request->get('id'));

            $chauff->setSexe($request->get('sexe'));
            $chauff->setEmail($request->get('email'));
            $chauff->setNom($request->get('nom'));
            $chauff->setTel($request->get('tel'));
            $chauff->setCin($request->get('cin'));
            $chauff->setUsername($request->get('username'));
            $chauff->setPlainPassword($request->get('password'));
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(true);
        }catch (\Exception $exception)
        {
            return new JsonResponse(false);
        }
    }

    /**
     * ajout d'un trajet.
     *
     * @Route("/modifierTuteur", name="tuteur_modifier")
     */
    public function modifierTuteur(Request $request)
    {
        try{
            $chauff=$this->getDoctrine()->getManager()->getRepository(Tuteur::class)->find($request->get('id'));
            $chauff->setSexe($request->get('sexe'));
            $chauff->setEmail($request->get('email'));
            $chauff->setNom($request->get('nom'));
            $chauff->setPrenom($request->get('prenom'));
            $chauff->setUsername($request->get('username'));
            $chauff->setPlainPassword($request->get('password'));
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(true);
        }catch (\Exception $exception)
        {
            return new JsonResponse(false);
        }
    }

    /**
     *
     *
     * @Route("/trajetspar/{id}", name="trajetsPar_api")
     */

    public function trajetsParAction($id)
    {


        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(

            'SELECT tr from AppBundle:Trajet tr,AppBundle:Chauffeur ev where ev.jardin   IN (SELECT DISTINCT m.id from AppBundle:Jardin m join m.abonnements ab 
        Join  ab.enfant e 
          where e.parent=:id)'
        )->setParameter('id',$id);
        $list = $query->getArrayResult();
return new JsonResponse($list);

    }

}
