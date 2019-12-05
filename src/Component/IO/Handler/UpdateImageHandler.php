<?php

namespace App\Component\IO\Handler;

use App\Component\Auth\Entity\TokenUser;
use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\IO\Exception\FileAlreadyExistsException;
use App\Component\IO\Service\ImageManager;
use App\Component\IO\Service\ImagePathProvider;
use App\Component\IO\Validation\UpdateImageValidation;
use App\Component\Person\Service\GuardianManager;
use App\Component\Validation\Exception\InvalidInputException;
use App\Entity\Image;
use Symfony\Component\Filesystem\Filesystem;

class UpdateImageHandler extends BaseUploadImage
{
    /**
     * @var GuardianManager
     */
    protected $guardianManager;

    /**
     * @var ImageManager;
     */
    protected $imageManager;

    /**
     * @var ImagePathProvider
     */
    protected $imagePathProvider;

    /**
     * @var UpdateImageValidation
     */
    protected $updateImageValidation;

    public function __construct(
        GuardianManager $guardianManager,
        ImageManager $imageManager,
        ImagePathProvider $imagePathProvider,
        UpdateImageValidation $updateImageValidation
    )
    {
        parent::__construct();
        $this->guardianManager = $guardianManager;
        $this->imageManager = $imageManager;
        $this->imagePathProvider = $imagePathProvider;
        $this->updateImageValidation = $updateImageValidation;
    }

    /**
     * @param array $data
     * @param int $imageId
     * @param TokenUser $tokenUser
     * @return Image|null
     * @throws FileAlreadyExistsException
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     * @throws \App\Component\IO\Exception\InvalidImageException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(array $data, int $imageId, TokenUser $tokenUser): ?Image
    {
        $validation = $this->updateImageValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }

        $guardian = $this->guardianManager->getById($tokenUser->getId());
        if (null === $guardian) {
            throw new ResourceNotFoundException();
        }

        $image = $this->imageManager->getById($imageId);
        if (null === $imageId) {
            throw new ResourceNotFoundException();
        }

        $this->handleUpdateImage($data, $image, $tokenUser);

        return $image;
    }

    /**
     * @param array $data
     * @param Image $image
     * @param TokenUser $tokenUser
     * @throws FileAlreadyExistsException
     * @throws \App\Component\IO\Exception\InvalidImageException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handleUpdateImage(array $data, Image $image, TokenUser $tokenUser): void
    {
        if (array_key_exists(UpdateImageValidation::FIELD_IMAGE_TEXT, $data)) {
            $image->setText($data[UpdateImageValidation::FIELD_IMAGE_TEXT]);
        }
        if (array_key_exists(UpdateImageValidation::FIELD_IMAGE_PATH, $data)) {
            $path = $data[UpdateImageValidation::FIELD_IMAGE_PATH];
            if ($image->getPath() !== $path) {
                // We must move the file to the new path
                $oldAbsolutePath = $this->imagePathProvider->buildUserAbsoluteImagePath(
                    $tokenUser->getUuid(),
                    $image->getPath()
                );
                $newAbsolutePath = $this->imagePathProvider->buildUserAbsoluteImagePath($tokenUser->getUuid(), $path);
                $filesystem = new Filesystem();
                if ($filesystem->exists($newAbsolutePath)) {
                    throw new FileAlreadyExistsException();
                }
                $filesystem->copy($oldAbsolutePath, $newAbsolutePath);
                $filesystem->remove($oldAbsolutePath);
                $image->setPath($path);
            }
        }
        if (array_key_exists(UpdateImageValidation::FIELD_IMAGE_CONTENT, $data)) {
            $this->uploadFile($data[UpdateImageValidation::FIELD_IMAGE_CONTENT], $image->getPath(), true);
        }

        $this->imageManager->update($image);
    }
}
