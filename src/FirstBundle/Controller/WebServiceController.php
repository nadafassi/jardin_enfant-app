<?php

namespace DorraBundle\Controller;

use AppBundle\Entity\Abonnement;
use AppBundle\Entity\Activite;
use AppBundle\Entity\Club;
use AppBundle\Entity\Enfant;
use AppBundle\Entity\Jardin;
use AppBundle\Entity\PartActivite;
use AppBundle\Entity\Rank;
use DorraBundle\Form\PartActiviteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Routing\Annotation\Route;

/**
 * WebService controller.
 *
 * @Route("webS")
 */
class WebServiceController extends Controller
{


    /**
     * lister toutes les activités.
     *
     * @Route("/listeact", name="activite_index")

     */
    public function ListerAAction()
    {

        // $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $activites = $em->getRepository('AppBundle:Activite')->getsActivite();



        return new JsonResponse($activites);

    }

    /**
     * @Route("/listeclub", name="java")
     */
    public function ListeAction()
    {

        // $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $clubs = $em->getRepository('AppBundle:Club')->getsClub();

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId(); // Change this to a valid method of your object
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $formatted= $serializer->Normalize($clubs, 'json');


        return new JsonResponse($formatted);

    }

    /**
     * afficher une activité.
     *
     * @Route("/showA/{id}")

     */
    public function AfficherAAction($id)
    {


        $em = $this->getDoctrine()->getManager();
        $activite = $em->getRepository('AppBundle:Activite')->getActivite($id);



        return new JsonResponse($activite);
    }




    /**
     * participer à une activité
     *
     * @Route("/partact/{id}/{ide}/{Date}", name="part_act")

     */
    public function ParticiperActAction(Request $request ,$id ,  $ide, $Date)
    {

            $part=new PartActivite();
            $part->setActivite($this->getDoctrine()->getManager()->getRepository(Activite::class)->findOneBy(array('id' => $id)));
            $part->setEnfant($this->getDoctrine()->getManager()->getRepository(Enfant::class)->find($ide));
            $part->setDate(New \DateTime($Date));

        $ex="succes";
        $em=$this->getDoctrine()->getManager();
        $em->persist($part);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($ex);
        return new JsonResponse($formatted);}



    /**
     * lister toutes les participations
     *
     * @Route("/listePart")

     */
public function ListeParticipationAction(){

    $em = $this->getDoctrine()->getManager();
    $part = $em->getRepository('AppBundle:PartActivite')->getsParticipation();



    return new JsonResponse($part);

}


    /**
     * afficher une activité.
     *
     * @Route("/verif/{id}/{ida}")

     */
public function Verifier($id, $ida){

    $em = $this->getDoctrine()->getManager();
    $veri = $em->getRepository('AppBundle:PartActivite')->verifier($id,$ida);



    return new JsonResponse($veri);
}


    /**
     * @Route("/addrank/{id}/{rank}/{idp}/{comment}", name="addrank")
     */

public function addRankAction(Request $request, $id, $rank, $idp, $comment){


    $ranke= new Rank();

    $ranke->setIdClub($id);

    $ranke->setComment($comment);
    $ranke->setRank($rank);
    $ranke->setIdParent($idp);



    $ex="succes";
    $em=$this->getDoctrine()->getManager();

    $em->persist($ranke);
    $em->flush();


    $serializer = new Serializer([new ObjectNormalizer()]);
    $formatted = $serializer->normalize($ex);
    return new JsonResponse($formatted);
}



    /**
     * @Route("/addact/{idc}/{type}/{det}/{date}", name="addact")
     */
public function AddActAction(Request $request,$idc,$type,$det,$date)
    {
        $act=new Activite('', New \DateTime('now'));
        $act->setClub($this->getDoctrine()->getManager()->getRepository(Club::class)->find($idc));

        $act->setPhoto("aaaa");
        $act->setDateDebut(New \DateTime('now'));
        $act->setDateFin(New \DateTime('now'));
        $act->setDateCreation(New \DateTime('now'));
        $act->setTypeact($type);
        $act->setDetailles($det);
        $act->setDate(New \DateTime($date));




        $ex="succes";
        $em=$this->getDoctrine()->getManager();
        $em->persist($act);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($ex);
        return new JsonResponse($formatted);}




}
