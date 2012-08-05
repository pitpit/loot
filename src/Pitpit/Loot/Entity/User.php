<?php

namespace Pitpit\Loot\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity(repositoryClass="Pitpit\Loot\Entity\AppRepository")
 * @Table(
 *   name="uuser",
 *   indexes={
 *     @index(name="is_developer_idx", columns={"is_developer"}),
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
     * @Column(type="string", length=255)
     */
    protected $email;

    /**
     *@Column(name="is_developer", type="boolean")
     */
    protected $isDeveloper;

    /**
     * When this user has been created
     *
     * @Column(type="datetime")
     */
    protected $created;

    /**
     * When this user has been modified for the last time
     *
     * @Column(type="datetime")
     */
    protected $modified;

    /**
     * All the apps this user can interact with
     * @OneToMany(targetEntity="UserApp", mappedBy="user")
     **/
    protected $userApps;

    public function __construct()
    {
        $this->userApps = new \Doctrine\Common\Collections\ArrayCollection();
        $this->created = new \Datetime();
        $this->modified = new \Datetime();
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