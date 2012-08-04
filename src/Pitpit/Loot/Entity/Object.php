<?php

namespace Pitpit\Loot\Entity;

use Doctrine\ORM\Mapping as ORM;
use Pitpit\Doctrine\DBAL\Point;

/**
 * @Entity
 * @Table(name="object")
 */
class Object
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="point")
     */
    protected $position;

    /**
     * @ManyToOne(targetEntity="App")
     * @JoinColumn(nullable=false)
     **/
    protected $app;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(nullable=false)
     **/
    protected $creator;

    public function __construct(App $app, User $creator, Point $position)
    {
        $this->setApp($app);
        $this->setCreator($creator);
        $this->setPosition($position);
    }

    public function getApp()
    {
        return $this->app;
    }

    public function setApp(App $app)
    {
        $this->app = $app;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator(User $creator)
    {
        $this->creator = $creator;
    }

    public function setPosition(Point $point)
    {
        $this->position = $point;
    }

    public function getPosition()
    {
        return $this->position;
    }
}