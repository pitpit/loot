<?php

namespace Pitpit\Loot\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity(repositoryClass="Pitpit\Loot\Entity\AppRepository")
 * @Table(
 *   name="uuser",
 *   indexes={
 *     @index(name="is_developer_idx", columns={"isDeveloper"}),
 *     @index(name="email_idx", columns={"email"})
 *   }
 * )
 *
 * - "user" is a reserved word and is bugprone with postgreSQL
 *
 */
class User
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="text", length=255)
     */
    protected $email;

    /**
     *@Column(type="boolean")
     */
    protected $isDeveloper;

    public function __construct($email)
    {
        $this->setEmail($email);
        $this->setIsDeveloper(false);
    }

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

    public function setIsDeveloper($isDeveloper)
    {
        $this->isDeveloper = $isDeveloper;
    }

    public function getIsDeveloper()
    {
        return $this->isDeveloper;
    }
}