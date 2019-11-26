<?php

namespace App\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;

class ConcreteTaskRepository extends TaskBaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConcreteTaskRepository::class);
    }
}
