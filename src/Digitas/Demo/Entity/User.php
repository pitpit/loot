<?php

namespace Digitas\Demo\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Damien Pitard <dpitard at digitas dot fr>
 * @copyright Digitas France
 *
 * @ORM\Entity(repositoryClass="Digitas\Demo\Entity\UserRepository")
 * @ORM\Table(
 *   name="user",
 *   uniqueConstraints={
 *       @ORM\UniqueConstraint(name="email_idx",columns={"email"})
 *   }
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     * @Assert\NotBlank(message = "Email can't be blank")
     * @Assert\Length(max="255")
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message = "Firstname can't be blank")
     * @Assert\Length(max="80")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message = "Firstname can't be blank")
     * @Assert\Length(max="80")
     */
    protected $lastname;

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }
}