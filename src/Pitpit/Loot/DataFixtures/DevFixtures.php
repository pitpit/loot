<?php

namespace Pitpit\Loot\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Wantlet\ORM\Point;
use Pitpit\Loot\Entity;

class DevFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new Entity\User('damien.pitard@gmail.com');
        $manager->persist($user);

        $app = new Entity\App('myApp1');
        $manager->persist($app);

        $object1 = new Entity\Object($app, $user, new Point(49.627, 1.175));
        $manager->persist($object1);

        $manager->flush();
    }
}