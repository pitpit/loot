<?php

namespace Pitpit\Loot\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity(repositoryClass="Pitpit\Loot\Entity\AppRepository")
 * @Table(
 *   name="app",
 *   indexes={
 *      @index(name="name_idx", columns={"name"})
 *   }
 * )
 */
class App
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="string", length=80)
     */
    protected $name;

    /**
     * @Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @Column(type="string", length=40)
     */
    protected $secret;

    /**
     * When this app has been created
     *
     * @Column(type="datetime")
     */
    protected $created;

    /**
     * When this app has been modified for the last time
     *
     * @Column(type="datetime")
     */
    protected $modified;

    /**
     * @OneToMany(targetEntity="UserApp", mappedBy="app", cascade={"persist", "remove"})
     **/
    protected $userApps;

    public function __construct()
    {
        $this->userApps = new \Doctrine\Common\Collections\ArrayCollection();
        $this->created = new \Datetime();
        $this->modified = new \Datetime();
        $this->resetSecret();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $name = strtolower($name);

        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function addUser(User $user, $role)
    {
        $this->userApps[] = new UserApp($this, $user, $role);
    }

    protected function resetSecret()
    {
        $this->secret = sha1(uniqid());
    }

    public function getSecret()
    {
        return $this->secret;
    }
}