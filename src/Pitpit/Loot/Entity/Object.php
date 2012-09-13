<?php

namespace Pitpit\Loot\Entity;

use Doctrine\ORM\Mapping as ORM;
use Pitpit\Geo\Point;
use Pitpit\Geo\LocatableInterface;

/**
 * @Entity
 * @Table(name="object")
 */
class Object implements LocatableInterface
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
    protected $location;

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

    public function __construct(App $app, User $creator, Point $location)
    {
        $this->setApp($app);
        $this->setCreator($creator);
        $this->setLocation($location);
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

    public function setLocation(Point $point)
    {
        $this->location = $point;
    }

    public function getLocation()
    {
        return $this->location;
    }
}