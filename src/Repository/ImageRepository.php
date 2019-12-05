<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Common\Persistence\ManagerRegistry;

class ImageRepository extends BaseRepository
{
    /**
     * GuardianRepository constructor.
     * @param ManagerRegistry $registry
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }
}
