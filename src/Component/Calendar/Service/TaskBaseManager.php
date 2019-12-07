<?php

namespace App\Component\Calendar\Service;

use App\Entity\TaskBase;
use App\Component\Calendar\Repository\TaskBaseRepository;
use DateTime;

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

    /**
     * @param DateTime $day
     * @param int $kidId
     * @return TaskBase[]
     */
    public function getTasks(DateTime $day, int $kidId): array
    {
        return $this->getEntityRepository()->getTasks($day, $kidId);
    }
}
