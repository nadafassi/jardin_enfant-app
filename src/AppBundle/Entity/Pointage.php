<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Pointage
 *
 * @ORM\Table(name="Pointage")
 * @ORM\Entity(repositoryClass="PointageRepository")
 */
class Pointage
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    /**
     * @ORM\ManyToOne(targetEntity="Chauffeur", inversedBy="pointage")
     * @JoinColumn(name="chauffeur_id", referencedColumnName="id")
     */
    private $chauffeur;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Pointage
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Pointage
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}

