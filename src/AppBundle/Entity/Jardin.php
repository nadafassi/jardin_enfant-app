<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Jardin
 *
 * @ORM\Table(name="jardin")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JardinRepository")
 */
class Jardin
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
     *
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=255)
     */
    private $description;
    /**
     * @var string
     *
     * @ORM\Column(name="numtel", type="string", length=255)
     */
    private $numtel;

    /**
     * @var float
     *
     * @ORM\Column(name="Tarif", type="float")
     */
    private $tarif;


    /**
     * @var string
     *
     * @ORM\Column(name="Adresse", type="string", length=255)
     */
    private $adresse;
    /**
     * @var string
     *
     * @ORM\Column(name="Etat", type="string", length=255)
     */
    private $etat;

    /**
     * @OneToMany(targetEntity="Abonnement", mappedBy="jardin")
     */
    private $abonnements;

    /**
     * @ORM\OneToOne(targetEntity="Responsable", mappedBy="jardin")
     */
    private $responsable;

    /**
     * @OneToMany(targetEntity="Paiement", mappedBy="jardin")
     */
    private $paiements;

    /**
     * @OneToMany(targetEntity="Messages", mappedBy="jardin")
     */
    private $messages;

    /**
     * @OneToMany(targetEntity="Club", mappedBy="jardin")
     */
    private $clubs;

    /**
     * @OneToMany(targetEntity="Tuteur", mappedBy="jardin")
     */
    private $tureurs;
    /**
     * @OneToMany(targetEntity="Evenement", mappedBy="jardin")
     */
    private $evenements;

    /**
     * @OneToMany(targetEntity="Chauffeur", mappedBy="jardin")
     */
    private $chauffeurs;

    /**
     * @return mixed
     */
    public function getPaiements()
    {
        return $this->paiements;
    }

    /**
     * @param mixed $paiements
     */
    public function setPaiements($paiements)
    {
        $this->paiements = $paiements;
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

    /**
     * @return mixed
     */
    public function getClubs()
    {
        return $this->clubs;
    }

    /**
     * @param mixed $clubs
     */
    public function setClubs($clubs)
    {
        $this->clubs = $clubs;
    }

    /**
     * @return mixed
     */
    public function getTureurs()
    {
        return $this->tureurs;
    }

    /**
     * @param mixed $tureurs
     */
    public function setTureurs($tureurs)
    {
        $this->tureurs = $tureurs;
    }

    /**
     * @return mixed
     */
    public function getEvenements()
    {
        return $this->evenements;
    }

    /**
     * @param mixed $evenements
     */
    public function setEvenements($evenements)
    {
        $this->evenements = $evenements;
    }

    /**
     * @return mixed
     */
    public function getChauffeurs()
    {
        return $this->chauffeurs;
    }

    /**
     * @param mixed $chauffeurs
     */
    public function setChauffeurs($chauffeurs)
    {
        $this->chauffeurs = $chauffeurs;
    }



    /**
     * @return mixed
     */
    public function getAbonnements()
    {
        return $this->abonnements;
    }

    /**
     * @param mixed $abonnements
     */
    public function setAbonnements($abonnements)
    {
        $this->abonnements = $abonnements;
    }

    /**
     * @return mixed
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * @param mixed $responsable
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    }

    /**
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param string $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
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
     * Set name
     *
     * @param string $name
     *
     * @return Jardin
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Jardin
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set tarif
     *
     * @param float $tarif
     *
     * @return Jardin
     */
    public function setTarif($tarif)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return float
     */
    public function getTarif()
    {
        return $this->tarif;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Jardin
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @return string
     */
    public function getNumtel()
    {
        return $this->numtel;
    }

    /**
     * @param string $numtel
     */
    public function setNumtel($numtel)
    {
        $this->numtel = $numtel;
    }




}

