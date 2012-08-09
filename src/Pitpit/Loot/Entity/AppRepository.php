<?php

namespace Pitpit\Loot\Entity;

use Doctrine\ORM\EntityRepository;

class AppRepository extends EntityRepository
{
    public function findByUserId($userId, $limit = null)
    {
        //@todo
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.userApps', 'ua')
            ->where('ua.user = :userId')
            ->setParameter('userId', $userId)
            ->add('orderBy', 'a.name ASC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findOneByNameAndUserId($name, $userId)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.userApps', 'ua')
            ->where('ua.user = :userId')
            ->andWhere('a.name = :name')
            ->setParameter('name', $name)
            ->setParameter('userId', $userId)
            ->setMaxResults(1);

        $results = $qb->getQuery()->getResult();
        if (count($results) == 0) {
            return null;
        }

        return $results[0];
    }

    public function findOneByIdAndUserId($id, $userId)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.userApps', 'ua')
            ->where('ua.user = :userId')
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->setParameter('userId', $userId)
            ->setMaxResults(1);

        $results = $qb->getQuery()->getResult();
        if (count($results) == 0) {
            return null;
        }

        return $results[0];
    }
}