<?php

namespace RaedBundle\Controller;

use AppBundle\Entity\Enfant;
use AppBundle\Entity\Jardin;
use RaedBundle\models\jardinmodels;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Default controller.
 *
 * @Route("default")
 */

class DefaultController extends Controller
{

    /**
     * @Route(
     *      "/map",
     *      name="getJardinMap"
     * )
     * @Method("GET")
     */
    public function mapJson()
    {


        $liste=$this->getDoctrine()->getManager()->getRepository(Jardin::class)->findAll();
        $finallist=array();
        foreach ($liste as $ls)
        {
            $json = file_get_contents('https://geocoder.ls.hereapi.com/6.2/geocode.json?searchtext='.$ls->getAdresse().'&gen=9&apiKey=CxxCHigH6e2itFdUuYEJdiNCKYOFT2wwtIF2QxxIjiw');
            $obj = json_decode($json);
            $map = new jardinmodels();
            $map->setId($ls->getId());
            $map->setLatitude($obj->Response->View[0]->Result[0]->Location->DisplayPosition->Latitude);
            $map->setLongitude($obj->Response->View[0]->Result[0]->Location->DisplayPosition->Longitude);
            $map->setAdresse($ls->getAdresse());
            $map->setName($ls->getName());
            $map->setNumtel($ls->getNumtel());
            array_push($finallist, $map);
        }

        $serializer = new Serializer([new ObjectNormalizer()]);

        $dataJson = $serializer->normalize($finallist);

        return new JsonResponse($dataJson);
    }
    /**
     * Lists all trajet entities.
     *
     * @Route("/mapdesjardins", name="jardin_map")
     * @Method("GET")
     */
    public function jardinsAction()
    {

        return $this->render('@Raed/Default/index.html.twig');
    }


}
