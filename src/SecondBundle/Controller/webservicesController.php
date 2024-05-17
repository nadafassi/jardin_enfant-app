<?php

namespace EmnaBundle\Controller;

use AppBundle\Entity\Categorie;
use AppBundle\Entity\Enfant;
use AppBundle\Entity\Evenement;
use AppBundle\Entity\Jardin;
use AppBundle\Entity\Parents;
use AppBundle\Entity\Participer;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

//use Symfony\Component\Routing\Annotation\Route;


/**
 * WebServices controller.
 *
 * @Route("/Api")
 */
class webservicesController extends Controller
{
    //liste des catégories pour le resp jardin

    /**
     *@Route("/categories", name="categories_api")

     */

    public function categoriesAPiAction()
    {
        //ît works
        $cat = $this->getDoctrine()->getManager();

        $cat = $this->getDoctrine()->getManager();
        $query = $cat->createQuery(
            'SELECT c
            FROM AppBundle:Categorie c');


        $list = $query->getArrayResult();
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $formatted = $serializer->normalize($list, 'json');


        return new JsonResponse($formatted);
    }


    /**
     *@Route("/categorieslib", name="categorieslib_api")

     */

    public function categorieslibAction()
    {
        //ît works
        $cat = $this->getDoctrine()->getManager();

        $cat = $this->getDoctrine()->getManager();
        $query = $cat->createQuery(
            'SELECT c.libelle
            FROM AppBundle:Categorie c');


        $list = $query->getArrayResult();
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $formatted = $serializer->normalize($list, 'json');


        return new JsonResponse($formatted);
    }





    /**
     *
     *
     * @Route("/listevents", name="lisevenement_api")
     */
    //liste des événements: pour parents et resp jardin

    public function evenemenetAction()
    {
        $em = $this->getDoctrine()->getManager();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT e
            FROM AppBundle:Evenement e');


        $list = $query->getArrayResult();
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId(); // Change this to a valid method of your object
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $formatted = $serializer->normalize($list, 'json');


        return new JsonResponse($formatted);
    }





    /**
     *
     *
     * @Route("/listeventsJar/{idj}", name="lisevenementJar_api")
     */
    //liste des événements d'un jardin

    public function evenemenetJarAction($idj)
    {

        $em = $this->getDoctrine()->getManager();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT e.id, e.titre,e.description,e.date,e.image,c.libelle
            FROM AppBundle:Evenement e,AppBundle:Categorie c
            Where e.jardin=:idj and e.categorie=c.id')


        ->setParameter('idj',$idj);

        $list = $query->getArrayResult();



        return new JsonResponse($list);
    }

//liste des events du jardin auquel l'enf est inscrit

    /**
     *
     *
     * @Route("/event/{id}", name="evenement_api")
     */

    public function evenemenetsAction($id)
    {


        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(

            'SELECT ev from AppBundle:Evenement ev where ev.jardin IN (SELECT DISTINCT m.id from AppBundle:Jardin m join m.abonnements ab 
        Join  ab.enfant e 
          where e.parent=:id)'
)

            ->setParameter('id', $id);

        $list = $query->getArrayResult();


        return new JsonResponse($list);
    }


    //get event
    /**
     *
     *
     * @Route("/getevent/{ide}", name="oneevent_api")
     */

    public function geteventAction($ide)
    {


        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(

            'SELECT ev from AppBundle:Evenement ev where ev.id=:id'
        )

            ->setParameter('id', $ide);

        $event = $query->getArrayResult();


        return new JsonResponse($event[0]);



    }
//ajouter événement pour le resp jardin

    /**
     * @Route("/ajoutevent/{idj}/{titre}/{description}/{date}/{idc}", name="ajouteve_api",methods={"GET"})

     */

    public function AjoutereventAction(Request $request, $idj, $titre, $description,$date,$idc)
    {
        $ev = new Evenement();
        $ev->setJardin($this->getDoctrine()->getManager()->getRepository(Jardin::class)->find($idj));
        $ev->setCategorie($this->getDoctrine()->getManager()->getRepository(Categorie::class)->find($idc));

        $ev->setDate(New \DateTime($date));

        $ev->setTitre($titre);
        $ev->setDescription($description);
        //$ev->setImage($image);


        $ex = "succes";
        $em = $this->getDoctrine()->getManager();
        $em->persist($ev);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($ex);
        return new JsonResponse($formatted);
    }


    //supprimer un événement pour resp jardin

    /**
     *
     *
     * @Route("/suppevent/{id}", name="suppevenement_api")
     */
    public function supprimereventAction($id)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($em->getRepository(Evenement::class)->find($id));
            $em->flush();
            return new JsonResponse(true);

        } catch (Exception $exception) {
            return new JsonResponse(false);
        }
    }


//modifier événement pour resp jardin

    /**
     * @Route("/editevent/{id}/{titre}/{description}/{date}/{idc}", name="modeve")
     */

    public function ModifierevefAction(Request $request, $id, $titre, $description, $date,$idc)
    {
        try {
            $evenement = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id);
            $evenement->setCategorie($this->getDoctrine()->getManager()->getRepository(Categorie::class)->find($idc));

            $evenement->setTitre($titre);
            $evenement->setDescription($description);
            $evenement->setDate(New \DateTime($date));


            $em = $this->getDoctrine()->getManager();

            $em->flush();


            return new JsonResponse(true);

        } catch (Exception $ex) { return JsonResponse(false);
        }
    }



// supprimer catégorie
    /**
     *
     *
     * @Route("/suppcat/{id}", name="suppcat_api")
     */
   /* public function supprimercatAction($id)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($em->getRepository(Categorie::class)->find($id));
            $em->flush();
            return new JsonResponse(true);

        } catch (Exception $exception) {
            return new JsonResponse(false);
        }
    }
*/
//Ajouter catégorie

    /**
     * @Route("/ajoutercat/{libelle}", name="ajouteve")
     * @throws Exception
     */
/*
    public function AjoutercatAction(Request $request, $libelle)
    {
        $ca = new Categorie();
        //$ca->setJardin($this->getDoctrine()->getManager()->getRepository(Jardin::class)->find($idj));

        $ca->setLibelle($libelle);

        $ex = "succes";
        $em = $this->getDoctrine()->getManager();
        $em->persist($ca);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($ex);
        return new JsonResponse($formatted);
    }

*/


        /**
         *
         * @Route("/modifierEvenement", name="event_modifier")
         */
    public function modifierEvenement(Request $request)
    {
        try{
            $ev=$this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($request->get('id'));

            $ev->setTitre($request->get('titre'));
            $ev->setDescription($request->get('description'));
            $ev->setDate($request->get('date'));
            $ev->setCategorie($request->get('categorie'));
            $ev->getDoctrine()->getManager()->flush();

            return new JsonResponse(true);

        }catch (\Exception $exception)
        {
            return new JsonResponse(false);
        }
    }




    /**
     * participer à un event
     *
     * @Route("/partEvent/{idev}/{idenf}", name="part_ev")

     */
    public function ParticiperEvenetAction(Request $request ,$idev,  $idenf)
    {

        $part=new Participer();
        $part->setEvenement($this->getDoctrine()->getManager()->getRepository(Evenement::class)->findOneBy(array('id' => $idev)));
        $part->setEnfant($this->getDoctrine()->getManager()->getRepository(Enfant::class)->find($idenf));

        $ex="succes";
        $em=$this->getDoctrine()->getManager();
        $em->persist($part);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($ex);
        return new JsonResponse($formatted);}


// liste des participation aux evenements par jardin

    /**
     * lister toutes les participations
     *
     * @Route("/listePart")

     */
    public function ListeParticipationAction(){

        $em = $this->getDoctrine()->getManager();
        $part = $em->getRepository('AppBundle:Participer')->getsParticipations();



        return new JsonResponse($part);

    }



    /**
     *
     * @Route("/affEv/{id}")

     */
    public function AfficherEvAction($id)
    {


        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Evenement')->getEvenement($id);



        return new JsonResponse($event);
    }


    /**
     *
     * @Route("/verifierr/{id}/{ide}")

     */
    public function Verifier($id, $ide){

        $em = $this->getDoctrine()->getManager();
        $veri = $em->getRepository('AppBundle:Participer')->verifier($id,$ide);



        return new JsonResponse($veri);
    }



    /**
     *
     * @Route("/EventParticipants")

     */
    public function EventParticipantsAction(){

        $em = $this->getDoctrine()->getManager();
        $part = $em->getRepository('AppBundle:Participer')->GetEventParticipants();



        return new JsonResponse($part);

    }






}


