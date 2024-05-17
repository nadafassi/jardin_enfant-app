<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Chauffeur
 *
 * @ORM\Table(name="chauffeur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChauffeurRepository")
 */
class Chauffeur extends User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Assert\Regex(pattern="/[0-9]/")
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="cin", type="string", length=255)
     */
    private $cin;

    /**
     * @var string
     *@Assert\NotBlank(message="veuillez saisir le nom et prenom")
     * @Assert\Regex(pattern="/^[a-z]+$/i",htmlPattern = "^[a-zA-Z]+$")
     * @ORM\Column(name="Nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     * @Assert\Regex(pattern="/[0-9]/")
     *
     * @ORM\Column(name="tel", type="string", length=255)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=255)
     */
    private $sexe;

    /**
     * @OneToMany(targetEntity="Pointage", mappedBy="chauffeur")
     */
    private $pointage;

    /**
     * @OneToMany(targetEntity="Trajet", mappedBy="chauffeur")
     */
    private $trajet;

    /**
     * @ORM\ManyToOne(targetEntity="Jardin", inversedBy="chauffeurs")
     * @ORM\JoinColumn(name="jardin_id", referencedColumnName="id")
     */
    private $jardin;



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
     * @return mixed
     */
    public function getPointage()
    {
        return $this->pointage;
    }

    /**
     * @param mixed $pointage
     */
    public function setPointage($pointage)
    {
        $this->pointage = $pointage;
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
     * Set cin
     *
     * @param string $cin
     *
     * @return Chauffeur
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return string
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Chauffeur
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
     * Set tel
     *
     * @param string $tel
     *
     * @return Chauffeur
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     *
     * @return Chauffeur
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

    /**
     * @return mixed
     */
    public function getTrajet()
    {
        return $this->trajet;
    }

    /**
     * @param mixed $trajet
     */
    public function setTrajet($trajet)
    {
        $this->trajet = $trajet;
    }

}

