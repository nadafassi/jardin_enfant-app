<?php

namespace SamiBundle\Controller;

use AppBundle\Entity\Abonnement;
use AppBundle\Entity\Activite;
use AppBundle\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;

/**
 * Default controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{

    /**
     *
     *
     * @Route("/dashboard", name="dashboard")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $abo=$this->getDoctrine()->getManager()->getRepository(Abonnement::class)->countEnfants($user->getJardin());
$montant=0;
        foreach ($abo as $abonnement)
{
    $montant=$montant+$abonnement->getMontant();
}
     $act=$this->getDoctrine()->getManager()->getRepository(Activite::class)->getActivities($user->getJardin());
        $event=$this->getDoctrine()->getManager()->getRepository(Evenement::class)->findBy(array('jardin'=>$user->getJardin()));


        return $this->render("@Sami/Default/index.html.twig",array('nbEnfants'=>sizeof($abo),
            'montant'=>$montant,
            'act'=>sizeof($act),
            'event'=>sizeof($event)));
    }

}
