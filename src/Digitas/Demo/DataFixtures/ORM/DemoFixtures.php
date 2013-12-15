<?php

namespace Digitas\Demo\DetaFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Digitas\Demo\Entity;

class DemoFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user1 = new Entity\User();
        $user1->setEmail('damien.pitard@digitas.fr');
        $user1->setFirstname('Damien');
        $user1->setLastname('Pitard');
        $manager->persist($user1);

        $user2 = new Entity\User();
        $user2->setEmail('pierre-louis.launay@digitas.fr');
        $user2->setFirstname('Pierre-Louis');
        $user2->setLastname('Launay');
        $manager->persist($user2);
        
        $manager->flush();
    }
}