<?php

namespace App\Component\Calendar\Repository;

use App\Entity\CronTask;
use Doctrine\Common\Persistence\ManagerRegistry;

class CronTaskRepository extends TaskBaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CronTask::class);
    }
}
