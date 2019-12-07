<?php

namespace App\Component\IO\Handler;

use App\Component\Auth\Entity\TokenUser;
use App\Component\Common\Exception\AccessDeniedException;
use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\IO\Service\ImageManager;
use App\Component\IO\Service\ImagePathProvider;
use App\Entity\Image;
use Doctrine\ORM\ORMException;
use Symfony\Component\Filesystem\Filesystem;

class DeleteImageHandler
{
    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @var ImagePathProvider
     */
    protected $imagePathProvider;

    public function __construct(ImageManager $imageManager, ImagePathProvider $imagePathProvider)
    {
        $this->imageManager = $imageManager;
        $this->imagePathProvider = $imagePathProvider;
    }

    /**
     * @param int $imageId
     * @param TokenUser $tokenUser
     * @return Image
     * @throws AccessDeniedException
     * @throws ResourceNotFoundException
     */
    public function handle(int $imageId, TokenUser $tokenUser): Image
    {
        $image = $this->imageManager->getById($imageId);
        if (null === $imageId) {
            throw new ResourceNotFoundException();
        }
        if (Image::TYPE_USER !== $image->getType()) {
            throw new AccessDeniedException();
        }
        if ($image->getGuardian()->getId() !== $tokenUser->getId()) {
            throw new AccessDeniedException();
        }

        try {
            $this->imageManager->remove($image, true);
        } catch (ORMException $e) {
            // The image probably does not exist. Return not found.
            throw new ResourceNotFoundException();
        }

        $filesystem = new Filesystem();
        $filesystem->remove(
            $this->imagePathProvider->buildUserAbsoluteImagePath($tokenUser->getUuid(), $image->getPath())
        );

        return $image;
    }
}
