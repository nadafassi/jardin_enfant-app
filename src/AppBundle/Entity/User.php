<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"T" = "Tuteur", "R" = "Responsable","P"="Parents","C"="Chauffeur","U"="User"})
 */
class User extends BaseUser
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
     * @ORM\OneToMany(targetEntity="Enfant", mappedBy="parent")
     */
    private $messages;

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
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

}