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
     * @return BaseRepository
     */
    public function getEntityRepository()
    {
        return $this->entityRepository;
    }

    /**
     * @param $id
     * @throws \Doctrine\ORM\ORMException
     */
    public function getReference($id)
    {
        return $this->getEntityRepository()->getReference($id);
    }
}
