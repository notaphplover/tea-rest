<?php

namespace App\Component\Calendar\Service;

use App\Entity\TaskBase;
use App\Repository\TaskBaseRepository;

/**
 * @method TaskBaseRepository getEntityRepository()
 */
abstract class TaskBaseManager extends TaskFragmentBaseManager
{
    /**
     * @param TaskBase $task
     * @return bool
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function areCollisions(TaskBase $task): bool
    {
        return $this->getEntityRepository()->areCollisions($task);
    }
}
