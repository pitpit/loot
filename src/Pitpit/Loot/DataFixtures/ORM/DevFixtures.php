<?php

namespace Pitpit\Loot\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Pitpit\Geo\Point;
use Pitpit\Loot\Entity;

class DevFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new Entity\User();
        $user->setIsDeveloper(true);
        $user->setEmail('damien.pitard@gmail.com');
        $manager->persist($user);

        $app1 = new Entity\App();
        $app1->setName('myApp1');
        $app1->addUser($user, Entity\UserApp::CREATOR_ROLE);
        $app1->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur tincidunt semper massa vitae pharetra. Sed viverra arcu eu libero gravida ornare tincidunt est ornare');
        $manager->persist($app1);

        $app2 = new Entity\App();
        $app2->setName('myApp2');
        $app2->addUser($user, Entity\UserApp::CREATOR_ROLE);
        $manager->persist($app2);

        $object1 = new Entity\Object($app1, $user, new Point(49.627, 1.175));
        $manager->persist($object1);

        $manager->flush();
    }
}