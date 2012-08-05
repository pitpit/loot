<?php

namespace Pitpit\Loot\Entity;

use Doctrine\ORM\EntityRepository;

class AppRepository extends EntityRepository
{
    public function findByUserId($userId)
    {
        //@todo
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.userApps', 'ua')
            ->leftJoin('ua.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId);

        $query = $qb->getQuery();
        $results = $query->getResult();

        return $results;
    }
}