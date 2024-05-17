<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PartActivite
 *
 * @ORM\Table(name="part_activite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PartActiviteRepository")
 */
class PartActivite
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
     * @ORM\Column(name="Date", type="date")
     */
    private $date;


    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Enfant")
     * @ORM\JoinColumn(name="enfant_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $enfant;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Activite")
     * @ORM\JoinColumn(name="activite_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $Activite;
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
     * @return PartActivite
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
     * @return mixed
     */
    public function getEnfant()
    {
        return $this->enfant;
    }

    /**
     * @param mixed $enfant
     */
    public function setEnfant($enfant)
    {
        $this->enfant = $enfant;
    }

    /**
     * @return mixed
     */
    public function getActivite()
    {
        return $this->Activite;
    }

    /**
     * @param mixed $Activite
     */
    public function setActivite($Activite)
    {
        $this->Activite = $Activite;
    }


}

