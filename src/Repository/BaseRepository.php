<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class BaseRepository extends ServiceEntityRepository
{
    /**
     * Determines if an entity is managed by doctrine.
     * @param $entity
     * @return bool
     */
    public function isManaged($entity): bool
    {
        return $this->_em->contains($entity);
    }

    /**
     * @param $id
     * @throws \Doctrine\ORM\ORMException
     */
    public function getReference($id)
    {
        $this->_em->getReference($this->_entityName, $id);
    }

    /**
     * @param $entity
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($entity, bool $commit = true): void
    {
        $this->_em->persist($entity);
        if ($commit) {
            $this->_em->flush();
        }
    }
}
