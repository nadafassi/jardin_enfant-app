<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Trajet
 *
 * @ORM\Table(name="trajet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TrajetRepository")
 */
class Trajet
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
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     * @Assert\Regex(pattern="/[0-9]{1,2}h[0-9]{1,2}/")
     * @ORM\Column(name="heure", type="string", length=255)
     */
    private $heure;
    /**
     * @ORM\ManyToOne(targetEntity="Chauffeur", inversedBy="trajet")
     * @ORM\JoinColumn(name="chauffeur_id", referencedColumnName="id")
     */
    private $chauffeur;

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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Trajet
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
     * Set heure
     *
     * @param string $heure
     *
     * @return Trajet
     */
    public function setHeure($heure)
    {
        $this->heure = $heure;

        return $this;
    }

    /**
     * Get heure
     *
     * @return string
     */
    public function getHeure()
    {
        return $this->heure;
    }

    /**
     * @return mixed
     */
    public function getChauffeur()
    {
        return $this->chauffeur;
    }

    /**
     * @param mixed $chauffeur
     */
    public function setChauffeur($chauffeur)
    {
        $this->chauffeur = $chauffeur;
    }

}

