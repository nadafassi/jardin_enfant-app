<?php

namespace EmnaBundle\Controller;

use AppBundle\Entity\Enfant;
use AppBundle\Entity\Evenement;
use AppBundle\Entity\Jardin;
use AppBundle\Entity\Participer;
use EmnaBundle\Form\ParticiperType;
use EmnaBundle\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Evenement controller.
 *
 * @Route("evenement")
 */
class EvenementController extends Controller
{
    /**
     *
     *
     * @\Symfony\Component\Routing\Annotation\Route("/events", name="evenements",methods={"GET"})
     */
    public function index1Action()
    {
        $em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository(Evenement::class)->findAll();

        return $this->render('@Emna/evenement/index1.html.twig', array(
            'evenements' => $evenements,
        ));
    }
    /**
     * Finds and displays a evenement entity.
     *
     * @Route("/evenet/{id}", name="evenements_show",methods={"GET"})
     */
    public function show1Action($id)
    {
        $em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository(Evenement::class)->find($id);

        return $this->render('@Emna/evenement/show1.html.twig', array(
            'event' => $evenements,

        ));
    }

    /**
     * Lists all evenement entities.
     *
     * @Route("/index", name="evenement_index",methods={"GET","POST"})
     */
    public function indexAction(Request $request)
    {$user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository('AppBundle:Evenement')->findBy(array('jardin'=>$user->getJardin()));
if ($request->isMethod("post"))
{
    $evenements = $em->getRepository('AppBundle:Evenement')->searchEvents($request->get('search'),$user->getJardin());
}
        return $this->render('@Emna/evenement/index.html.twig', array(
            'evenements' => $evenements,
        ));
    }
    /**
     * Lists all evenement entities.
     *
     * @Route("/stat", name="evenement_stat",methods={"GET"})
     */
    public function chartAction()
    {
$user=$this->container->get('security.token_storage')->getToken()->getUser();
$list=$this->getDoctrine()->getManager()->getRepository(Evenement::class)->findBy(array('jardin'=>$user->getJardin()));
$final=array();
foreach ($list as $ls)
{
    array_push($final,array($ls->getTitre(),sizeof($ls->getParticipation())));
}
        $series = array(
            array("type"=>"pie","name" => "Nombre de participants","data"=>$final)
        );

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Participation aux evenements');
        $ob->xAxis->title(array('text'  => "Evenements"));
        $ob->yAxis->title(array('text'  => "Participants"));
        $ob->series($series);

        return $this->render('@Emna/evenement/stat.html.twig', array(
            'chart' => $ob
        ));
    }

    /**
     *
     * @Route("/participer/{id}", name="evenement_participe",methods={"GET","POST"})
     */
    public function participerAction(Request $request,$id)
   {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
       $event = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id);
       $participe =new Participer();
       $participe->setEvenement($event);
       $q=$this->getDoctrine()->getManager()->getRepository(Enfant::class)->getmesenfant($user->getId(),$event->getJardin());

       $form = $this->createForm(ParticiperType::class, $participe,
           array('user'=>$q,'jardin'=>$event,));
       $form->handleRequest($request);

       if ($form->isSubmitted() ) {

           $em = $this->getDoctrine()->getManager();
           $em->persist($participe);
           $em->flush();

           return $this->redirectToRoute('evenements');
       }

       return $this->render('@Emna/evenement/participer.html.twig', array(
           'evenement' => $event,
           'id'=>$id,
           'form' => $form->createView(),
       ));


   }

    /**
     * Creates a new evenement entity.
     *
     * @Route("/new", name="evenement_new",methods={"GET","POST"})
     */
    public function newAction(Request $request)
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

            if ($form->isSubmitted() ) {


                $date = new \DateTime($request->get("date"));
                $evenement->setDate($date);
                $user = $this->container->get('security.token_storage')->getToken()->getUser();
                $jardin=$this->getDoctrine()->getManager()->getRepository(Jardin::class)->find($user->getJardin());

                $evenement->setJardin($jardin);


            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            return $this->redirectToRoute('evenement_show', array('id' => $evenement->getId()));
        }

        return $this->render('@Emna/evenement/new.html.twig', array(
            'evenement' => $evenement,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a evenement entity.
     *
     * @Route("/{id}", name="evenement_show",methods={"GET"})
     */
    public function showAction(Evenement $evenement)
    {
        $deleteForm = $this->createDeleteForm($evenement);

        return $this->render('@Emna/evenement/show.html.twig', array(
            'evenement' => $evenement,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing evenement entity.
     *
     * @Route("/{id}/edit", name="evenement_edit",methods={"GET","POST"})
     */
    public function editAction(Request $request, Evenement $evenement)
    {
        $deleteForm = $this->createDeleteForm($evenement);
        $editForm = $this->createForm(EvenementType::class, $evenement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() ) {

            $date = new \DateTime($request->get("date"));
            $evenement->setDate($date);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evenement_show', array('id' => $evenement->getId()));
        }

        return $this->render('@Emna/evenement/edit.html.twig', array(
            'evenement' => $evenement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a evenement entity.
     *
     * @Route("/delete/{id}", name="evenement_delete",methods={"DELETE"})
     */
    public function deleteAction(Request $request, Evenement $evenement)
    {
        $form = $this->createDeleteForm($evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($evenement);
            $em->flush();
        }

        return $this->redirectToRoute('evenement_index');
    }

    /**
     * Creates a form to delete a evenement entity.
     *
     * @param Evenement $evenement The evenement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Evenement $evenement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('evenement_delete', array('id' => $evenement->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }














}
