<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * MessagesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessagesRepository extends \Doctrine\ORM\EntityRepository
{


    public function getmessages($id)
    {
        $q=$this->getEntityManager()->createQuery("SELECT m from AppBundle:Parents p , AppBundle:Messages m where m.parent=p AND m.parent=:id ")
            ->setParameter('id',$id);
        return $query=$q->getResult();


    }
    public function getallmess($id)
    {
        $q=$this->getEntityManager()->createQuery("SELECT m from AppBundle:Messages m 
         LEFT JOIN m.parent p where m.date in(select MAX(l.date) from AppBundle:Messages l where l.parent=p AND m.jardin=l.jardin Group by l.parent) AND  m.jardin=:id   ORDER BY m.date DESC 
            ")->setParameter('id',$id);

        return $query=$q->getResult();


    }

    public  function getmessag($id){

        //for parent

    }
    public function getmine($id,$jar)
    {
        //for parent
        $q=$this->getEntityManager()->createQuery("SELECT m from AppBundle:Messages m 
         LEFT JOIN m.parent p where m.parent=:id AND  m.jardin in (:jar)  
            ")->setParameter('id',$id)->setParameter('jar',$jar);

        return $query=$q->getResult();


    }




    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// /////////////////////////////////////   WEB SERVICE METHODE //////////////////////////////////////////////////////////////////



    //for parent


    public function getlistjard($id){

        //get jard list to contact
        $q=$this->getEntityManager()
            ->createQuery("SELECT DISTINCT m.id, m.name,r.nom from AppBundle:Jardin m join m.abonnements ab 
        Join  ab.enfant e , AppBundle:Responsable r 
          where e.parent=:id AND r.jardin=m  ")
            ->setParameter('id',$id);

        return $query=$q->getResult();

    }

    //
    public function getjardmess($id,$jar){

        //get the messages for a specific kindergarten
        //id as parent id and jar as kindergarten id


        $q=$this->getEntityManager()
            ->createQuery("SELECT  m.id as mid, m.msg ,m.date ,s.id as sid ,j.name as jardin,j.id as jardid , p.nom as parenom ,p.prenom as pareprenom 
            from AppBundle:Parents p, AppBundle:Jardin j JOIN j.messages m  LEFT JOIN m.sender s 
                  where m.parent=:id AND m.jardin=:jar AND m.parent=p")
            ->setParameter('id',$id)->setParameter("jar",$jar);

        return $query=$q->getResult();

    }










    // for responsable





    public function getusermlist($id){

        //get user list the users who contacted the jardin
        $q=$this->getEntityManager()
            ->createQuery("SELECT  p.id as parid,p.nom, p.prenom, m.msg ,m.date as mdate from AppBundle:Messages m 
         LEFT JOIN m.parent p where m.date in(select MAX(l.date) from AppBundle:Messages l where l.parent=p AND m.jardin=l.jardin Group by l.parent) AND  m.jardin=:id   ORDER BY m.date DESC  ")
            ->setParameter('id',$id);

        return $query=$q->getResult();

    }






}
