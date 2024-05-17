<?php

namespace RaedBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Jardin;
use AppBundle\Entity\Paiement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
/**
 * WebService controller.
 *
 * @Route("Apijar")
 */
class WebservicesController extends Controller

{
    /**
     * @Route("/listjardin", name="java_listjardin",methods={"GET"})
     */

    public function indexAction()
    {



        // $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $jardin = $em->getRepository('AppBundle:Jardin')->getJardins();



        return new JsonResponse($jardin);
    }
    /**
     * @Route("/modifjardin", name="modijardin")
     */
    public function ModifierjardinfAction(Request $request)

    {

        $ide=$request->get("id");
        $name=$request->get("name");
        $description=$request->get("description");
        $numtel=$request->get("numtel");
        $adresse=$request->get("adresse");
        $tarif=$request->get("tarif");


        $jardin=$this->getDoctrine()->getManager()->getRepository(Jardin::class)->find($ide) ;
        $jardin->setName($name);
        $jardin->setDescription($description);
        $jardin->setNumtel($numtel);
        $jardin->setTarif($tarif);

        $jardin->setAdresse($adresse);

        $ex="succes";
        $em=$this->getDoctrine()->getManager();

        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($ex);
        return new JsonResponse($formatted);


    }




    /**
     * @Route("/listpaiement/{id}", name="java_paiementJardin1",methods={"GET"})
     */

    public function PaimentJardinAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $paiment = $em->createQuery('SELECT p.id ,p.montant ,p.date
    FROM AppBundle:Paiement p ,AppBundle:Jardin t,AppBundle:Responsable c WHERE t.id=c.jardin and p.jardin=t.id and p.jardin=:id'
        )
            ->setParameter('id',$id);
        $jardin = $paiment->getResult();

        return new JsonResponse($jardin);
    }


    /**
     * @Route("/paiement1", name="java_paiementJardin",methods={"GET"})
     */
    public function testPaimentAction(Request $request)
    {
        $mont=250;

        $id=$request->get("id1");



            $paiment = new Paiement();




            $paiment->setJardin($this->getDoctrine()->getRepository(Jardin::class)->find($id));
            $paiment->setDate(new \DateTime("now"));
            $paiment->setMontant($mont);



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($paiment);

            $entityManager->flush();

            if($entityManager->contains($paiment))
            { return new JsonResponse(true);

            }
            return new JsonResponse(false);


    }



}
