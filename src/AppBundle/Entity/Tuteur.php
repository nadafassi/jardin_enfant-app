<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Tuteur
 *
 * @ORM\Table(name="tuteur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TuteurRepository")
 */
class Tuteur extends User
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
     *@Assert\NotBlank(message="veuillez saisir le nom ")
     * @Assert\Regex(pattern="/^[a-z]+$/i",htmlPattern = "^[a-zA-Z]+$")
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
    /**
     * @var string
     *@Assert\NotBlank(message="veuillez saisir le prenom")
     * @Assert\Regex(pattern="/^[a-z]+$/i",htmlPattern = "^[a-zA-Z]+$")
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;
    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=255)
     */
    private $sexe;

    /**
     * @ORM\ManyToOne(targetEntity="Jardin", inversedBy="tureurs")
     * @ORM\JoinColumn(name="jardin_id", referencedColumnName="id")
     */
    private $jardin;

    /**
     * @ORM\OneToMany(targetEntity="Remarque", mappedBy="tuteur")
     */
    private $remarques;


/*
    /**
     * @return mixed
     */

  /*  public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
  /*  public function setUser($user)
    {
        $this->user = $user;
    }

*/
    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * @param string $sexe
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
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
     * @return mixed
     */
    public function getRemarques()
    {
        return $this->remarques;
    }

    /**
     * @param mixed $remarques
     */
    public function setRemarques($remarques)
    {
        $this->remarques = $remarques;
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
     * @return Tuteur
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

}
