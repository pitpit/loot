<?php

namespace Pitpit\Loot\Entity;

use Doctrine\ORM\Mapping as ORM;
use Wantlet\ORM\Point;

/**
 * @Entity()
 * @Table
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
     * @JoinColumn(name="app_id", referencedColumnName="id", nullable=false)
     **/
    protected $app;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     **/
    protected $author;

    public function __construct(App $app, User $author, Point $position)
    {
        $this->setApp($app);
        $this->setAuthor($author);
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

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
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