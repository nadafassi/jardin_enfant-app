<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;
/**
 * Enfant
 *
 * @ORM\Table(name="enfant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EnfantRepository")
 */
class Enfant
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
     *@Assert\Length(max = 20)
     * @Assert\Regex(pattern="/[a-zA-Z]/")
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *@Assert\Length(max = 20)
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var \DateTime

     * @ORM\Column(name="datenaiss", type="date")
     */
    private $datenaiss;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=255)
     */
    private $sexe;


    /**
     * @MaxDepth(1)
     * @OneToMany(targetEntity="Abonnement", mappedBy="enfant")
     */
    private $abonnements;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Parents", inversedBy="enfants")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;



    /**
     * @MaxDepth(1)
     * @ORM\OneToMany(targetEntity="Participer", mappedBy="enfant" )
     */
    private $participation;

    /**
     * @MaxDepth(1)
     * @ORM\OneToMany(targetEntity="PartActivite", mappedBy="enfant")
     */
    private $participerActivite;

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
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Enfant
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Enfant
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set datenaiss
     *
     * @param \DateTime $datenaiss
     *
     * @return Enfant
     */
    public function setDatenaiss($datenaiss)
    {
        $this->datenaiss = $datenaiss;

        return $this;
    }

    /**
     * Get datenaiss
     *
     * @return \DateTime
     */
    public function getDatenaiss()
    {
        return $this->datenaiss;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     *
     * @return Enfant
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }
}

