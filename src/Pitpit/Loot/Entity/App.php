<?php

namespace Pitpit\Loot\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity(repositoryClass="Pitpit\Loot\Entity\AppRepository")
 * @Table
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
     * @Column(type="text", length=255)
     */
    protected $name;

    /**
     * @Column(type="text", length=40)
     */
    protected $secret;

    public function __construct($name)
    {
        $this->setName($name);
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
        $this->name = $name;
    }

    protected function resetSecret()
    {
        $this->secret = sha1(uniqid());
    }
}