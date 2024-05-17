<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Club
 *
 * @ORM\Table(name="club")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClubRepository")
 */
class Club
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
     *@Assert\NotBlank(message="veuillez saisir le nom du club")
     * @Assert\Length(max=60)
     * @Assert\Regex(pattern="/[a-zA-Z]/")
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\Regex(pattern="/[a-zA-Z]/")
     * @ORM\Column(name="Description", type="string", length=255)
     */
    private $description;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $photo;

    /**
     * @var int
     *
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    public function setPhoto( $file )
    {
        $this->photo = $file;
    }

    /**
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }




    public function getPhoto()
    {
        return $this->photo;
    }
    /**
     * @OneToMany(targetEntity="Activite", mappedBy="club" )
     */
    private $activites;

    /**
     * @ORM\ManyToOne(targetEntity="Jardin", inversedBy="clubs")
     * @ORM\JoinColumn(name="jardin_id", referencedColumnName="id")
     */
    private $jardin;

    /**
     * @return mixed
     */
    public function getActivites()
    {
        return $this->activites;
    }

    /**
     * @param mixed $activites
     */
    public function setActivites($activites)
    {
        $this->activites = $activites;
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
     * Set name
     *
     * @param string $name
     *
     * @return Club
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
     * @return Club
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
}

