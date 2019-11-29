<?php

namespace App\Repository;

use App\Entity\ConcreteTask;
use Doctrine\Common\Persistence\ManagerRegistry;

class ConcreteTaskRepository extends TaskBaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConcreteTask::class);
    }
}
