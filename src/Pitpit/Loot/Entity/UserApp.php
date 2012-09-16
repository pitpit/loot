<?php

namespace Pitpit\Loot\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 * @Table(
 *   name="user_app"
 * )
 */
class UserApp
{
    const USER_ROLE = 1;
    const DEVELOPER_ROLE = 2;
    const ADMIN_ROLE = 4;
    const CREATOR_ROLE = 8;

    /**
     * @Id
     * @ManyToOne(targetEntity="App", inversedBy="userApps")
     * @JoinColumn(name="app_id", referencedColumnName="id")
     **/
    protected $app;

    /**
     * @Id
     * @ManyToOne(targetEntity="User", inversedBy="userApps")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $user;

    /**
     * @Column(type="integer")
     */
    protected $role;

    public function __construct(App $app, User $user, $role = self::USER_ROLE)
    {
        $this->app = $app;
        $this->user = $user;
        $this->role = $role;
    }
}