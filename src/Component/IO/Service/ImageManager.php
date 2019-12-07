<?php

namespace App\Component\IO\Service;

use App\Component\Common\Service\BaseManager;
use App\Entity\Image;
use App\Repository\ImageRepository;

/**
 * Class ImageManager
 * @package App\Component\IO\Service
 *
 * @method Image getById(int $id)
 * @method Image[] getByIds(int $id)
 * @method ImageRepository getEntityRepository()
 * @method Image getReference($id)
 * @method void remove(Image $entity, bool $commit = true)
 */
class ImageManager extends BaseManager
{
    public function __construct(ImageRepository $entityRepository)
    {
        parent::__construct($entityRepository);
    }

    /**
     * @param int $guardianId
     * @param int $pageNumber
     * @param int $pageSize
     * @return Image[]
     */
    public function getByGuardianAndPage(int $guardianId, int $pageNumber, int $pageSize): array
    {
        return $this->getEntityRepository()->getByGuardianAndPage($guardianId, $pageNumber, $pageSize);
    }

    /**
     * @param string $path
     * @param string $scope
     * @return Image|null
     */
    public function getByPathAndScope(string $path, string $scope): ?Image
    {
        return $this->getEntityRepository()->findOneBy([
            'path' => $path,
            'scope' => $scope
        ]);
    }
}
