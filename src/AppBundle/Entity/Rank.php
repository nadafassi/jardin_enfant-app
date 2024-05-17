<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rank
 *
 * @ORM\Table(name="rank")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RankRepository")
 */
class Rank
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
     * @var int
     *
     * @ORM\Column(name="id_parent", type="integer")
     */
    private $idParent;

    /**
     * @var int
     *
     * @ORM\Column(name="id_club", type="integer")
     */
    private $idClub;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank")
     */
    private $rank;


    /**
     * @var string
     *
     * @ORM\Column(name="comment")
     */
    private $comment ;

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
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
     * Set idParent
     *
     * @param integer $idParent
     *
     * @return Rank
     */
    public function setIdParent($idParent)
    {
        $this->idParent = $idParent;

        return $this;
    }

    /**
     * Get idParent
     *
     * @return int
     */
    public function getIdParent()
    {
        return $this->idParent;
    }

    /**
     * Set idClub
     *
     * @param integer $idClub
     *
     * @return Rank
     */
    public function setIdClub($idClub)
    {
        $this->idClub = $idClub;

        return $this;
    }

    /**
     * Get idClub
     *
     * @return int
     */
    public function getIdClub()
    {
        return $this->idClub;
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


}

