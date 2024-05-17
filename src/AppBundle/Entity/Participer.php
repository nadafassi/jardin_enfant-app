<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Participer
 *
 * @ORM\Table(name="participer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParticiperRepository")
 */
class Participer
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
     * @ORM\ManyToOne(targetEntity="Enfant", inversedBy="participation")
     * @ORM\JoinColumn(name="enfant_id", referencedColumnName="id",onDelete="cascade")
     */
    private $enfant;
    /**
     * @ORM\ManyToOne(targetEntity="Evenement", inversedBy="participation")
     * @ORM\JoinColumn(name="evenement_id", referencedColumnName="id",onDelete="cascade")
     */
    private $evenement;


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
    public function getEvenement()
    {
        return $this->evenement;
    }

    /**
     * @param mixed $evenement
     */
    public function setEvenement($evenement)
    {
        $this->evenement = $evenement;
    }

}

