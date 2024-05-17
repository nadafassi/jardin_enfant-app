<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EvenementRepository")
 */
class Evenement
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
     * @Assert\Regex(pattern="/[a-zA-Z]/")
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var /DateTime
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     * @Assert\Regex(pattern="/[a-zA-Z]/")
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", name="image",nullable=true)
     */
    private $image;


    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="evenements")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id",onDelete="cascade")
     */
    private $categorie;


    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="Jardin", inversedBy="evenements")
     * @ORM\JoinColumn(name="jardin_id", referencedColumnName="id")
     */
    private $jardin;
    /**
     * @MaxDepth(1)
     * @ORM\OneToMany(targetEntity="Participer", mappedBy="evenement",cascade={"remove"})
     */
    private $participation;

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * @return mixed
     */
    public function getJardin()
    {
        return $this->jardin;
    }

    /**
     * @param mixed $jardin
     */
    public function setJardin($jardin)
    {
        $this->jardin = $jardin;
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Evenement
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Evenement
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
     * Set description
     *
     * @param string $description
     *
     * @return Evenement
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

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }






}


