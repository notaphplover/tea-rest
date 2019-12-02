<?php

namespace App\Component\Common\Service;

use App\Repository\BaseRepository;

abstract class BaseManager
{
    /**
     * @var BaseRepository
     */
    private $entityRepository;

    /**
     * BaseManager constructor.
     * @param BaseRepository $entityRepository
     */
    public function __construct(BaseRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function flush(): void
    {
        $this->getEntityRepository()->flush();
    }

    /**
     * @param $id
     * @return object|null
     */
    public function getById($id)
    {
        return $this->getEntityRepository()->getById($id);
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getByIds(array $ids): array
    {
        return $this->getEntityRepository()->getByIds($ids);
    }

    /**
     * @return BaseRepository
     */
    public function getEntityRepository()
    {
        return $this->entityRepository;
    }

    /**
     * @param $id
     * @return bool|\Doctrine\Common\Proxy\Proxy|object|null
     * @throws \Doctrine\ORM\ORMException
     */
    public function getReference($id)
    {
        return $this->getEntityRepository()->getReference($id);
    }

    /**
     * @param $entity
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove($entity, bool $commit = true): void
    {
        $this->getEntityRepository()->remove($entity, $commit);
    }

    /**
     * @param $entity
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($entity, bool $commit = true): void
    {
        $this->getEntityRepository()->update($entity, $commit);
    }
}
