<?php

namespace App\Repository;

use App\Entity\ConcreteSubTask;
use Doctrine\Common\Persistence\ManagerRegistry;

class ConcreteSubTaskRepository extends TaskFragmentBaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConcreteSubTask::class);
    }
}
