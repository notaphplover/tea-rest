<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

abstract class BaseRepository extends ServiceEntityRepository
{
    /**
     * @var string
     */
    protected $idField;

    /**
     * BaseRepository constructor.
     * @param ManagerRegistry $registry
     * @param $entityClass
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        parent::__construct($registry, $entityClass);

        $meta = $this->_em->getClassMetadata($entityClass);
        $this->idField = $meta->getSingleIdentifierFieldName();
    }

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
     * @return object|null
     */
    public function getById($id)
    {
        return $this->findOneBy([$this->idField => $id]);
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getByIds(array $ids): array
    {
        if (0 === count($ids)) {
            return [];
        }
        $qb = $this->createQueryBuilder('e');
        return $qb
            ->where($qb->expr()->in('e.' . $this->idField, $ids))
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function flush(): void
    {
        $this->_em->flush();
    }

    /**
     * @param $id
     * @return bool|\Doctrine\Common\Proxy\Proxy|object|null
     * @throws \Doctrine\ORM\ORMException
     */
    public function getReference($id)
    {
        return $this->_em->getReference($this->_entityName, $id);
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

    /**
     * @param $entity
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove($entity, bool $commit = true): void
    {
        $this->_em->remove($entity);
        if ($commit) {
            $this->_em->flush();
        }
    }
}
