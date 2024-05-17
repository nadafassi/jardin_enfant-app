<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use AncaRebeca\FullCalendarBundle\Model\FullCalendarEvent;


/**
 * Activite
 *
 * @ORM\Table(name="activite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActiviteRepository")
 */
class Activite extends FullCalendarEvent
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *@Assert\NotBlank(message="veuillez saisir le type d'activite")
     * @Assert\Length(max=10)
     * @Assert\Regex(pattern="/[a-zA-Z]/")
     * @ORM\Column(name="typeact", type="string", length=255)
     */
    private $typeact;

    /**
     * @var string
     *@Assert\Length(max=200)
     * @Assert\Regex(pattern="/[a-zA-Z]/")
     * @ORM\Column(name="detailles", type="string", length=255)
     */
    private $detailles;


    /**
     * @ORM\Column(type="string")
     */
    private $photo;

    /**
     * @ORM\Column(type="date")
     */


    private $Date;


    /**
     *
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(name="dateDebut", type="date")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     *
     * @ORM\Column(name="dateFin", type="date")
     */
    private $dateFin;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messages", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userid;

    /**
     * @return mixed
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param mixed $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->Date;
    }

    /**
     * @param mixed $Date
     */
    public function setDate($Date)
    {
        $this->Date = $Date;
    }

    /**
     * @return mixed
     */
    public function getParticipation()
    {
        return $this->participation;
    }

    /**
     * @param mixed $participation
     */
    public function setParticipation($participation)
    {
        $this->participation = $participation;
    }




    public function setPhoto( $file )
    {
        $this->photo = $file;
    }

    public function getPhoto()
    {
        return $this->photo;
    }


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="date")
     */
    private $dateCreation;
    /**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="activites")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id", onDelete="cascade")
     */
    private $club;

    /**
     * @ORM\OneToMany(targetEntity="PartActivite", mappedBy="Activite")
     */
    private $participation;

    /**
     * @return mixed
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * @param mixed $club
     */
    public function setClub($club)
    {
        $this->club = $club;
    }



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set typeact
     *
     * @param string $typeact
     *
     * @return Activite
     */
    public function setTypeact($typeact)
    {
        $this->typeact = $typeact;

        return $this;
    }

    /**
     * Get typeact
     *
     * @return string
     */
    public function getTypeact()
    {
        return $this->typeact;
    }

    /**
     * Set detailles
     *
     * @param string $detailles
     *
     * @return Activite
     */
    public function setDetailles($detailles)
    {
        $this->detailles = $detailles;

        return $this;
    }

    /**
     * Get detailles
     *
     * @return string
     */
    public function getDetailles()
    {
        return $this->detailles;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTime $dateDebut
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param \DateTime $dateFin
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param \DateTime $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }


}