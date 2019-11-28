<?php

namespace App\Repository;

use App\Entity\TaskBase;
use function Doctrine\ORM\QueryBuilder;

abstract class TaskBaseRepository extends TaskFragmentBaseRepository
{
    /**
     * @param TaskBase $task
     * @return bool
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function areCollisions(TaskBase $task): bool
    {
        $qb = $this->createQueryBuilder('t');

        $query = $qb
            ->select('count(t)')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('t.kid', ':kid'),
                    $qb->expr()->lt('t.timeStart', ':timeEnd'),
                    $qb->expr()->gt('t.timeEnd', ':timeStart')
                )
            )->setParameters([
                'kid' => $task->getKid()->getId(),
                'timeStart' => $task->getTimeStart(),
                'timeEnd' => $task->getTimeEnd()
            ])->getQuery();
        return $query->getSingleScalarResult() > 0;
    }
}
