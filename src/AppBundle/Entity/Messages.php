<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Messages
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessagesRepository")
 */
class Messages
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
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;

    /**
     * @var string
     *@Assert\NotNull
     *@Assert\NotBlank(message="le message ne doit pas etre null")
     * @ORM\Column(name="msg", type="string", length=255)
     */
    private $msg;

    /**
     * @ORM\ManyToOne(targetEntity="Jardin", inversedBy="messages", fetch="EAGER")
     * @ORM\JoinColumn(name="jardin_id", referencedColumnName="id")
     */
    private $jardin;
    /**
     * @ORM\ManyToOne(targetEntity="Parents", inversedBy="messages", fetch="EAGER")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messages", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $sender;

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
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
     * Set date
     *
     * @param string $date
     *
     * @return Messages
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set msg
     *
     * @param string $msg
     *
     * @return Messages
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;

        return $this;
    }

    /**
     * Get msg
     *
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }
}

