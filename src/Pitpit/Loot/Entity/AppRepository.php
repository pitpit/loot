<?php

namespace Pitpit\Loot\Entity;

use Doctrine\ORM\EntityRepository;

class AppRepository extends EntityRepository
{
    public function findByUserId()
    {
        //@todo
    }
}