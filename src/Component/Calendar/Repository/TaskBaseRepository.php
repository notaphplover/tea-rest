<?php

namespace App\Component\Calendar\Repository;

use App\Entity\TaskBase;
use DateTime;

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

    /**
     * @param DateTime $day
     * @param int $kidId
     * @return TaskBase[]
     */
    public function getTasks(DateTime $day, int $kidId): array
    {
        $qb = $this->createQueryBuilder('t');

        $query = $qb->where(
            $qb->expr()->andX(
                $qb->expr()->eq('t.day', ':day'),
                $qb->expr()->eq('t.kid', ':kid')
            )
        )->setParameters([
            'day' => $day,
            'kid' => $kidId,
        ])->getQuery();

        return $query->getResult();
    }
}
