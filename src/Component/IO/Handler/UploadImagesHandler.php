<?php

namespace App\Component\IO\Handler;

use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\IO\Service\ImageManager;
use App\Component\IO\Service\ImageProvider;
use App\Component\IO\Validation\UploadImagesValidation;
use App\Component\Person\Service\GuardianManager;
use App\Component\Validation\Exception\InvalidInputException;
use App\Entity\Guardian;
use App\Entity\Image;

class UploadImagesHandler extends BaseUploadImage
{
    /**
     * @var GuardianManager
     */
    protected $guardianManager;

    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @var ImageProvider
     */
    protected $imageProvider;

    /**
     * @var UploadImagesValidation
     */
    protected $uploadFilesValidation;

    /**
     * UploadImageHandler constructor.
     * @param GuardianManager $guardianManager
     * @param ImageManager $imageManager
     * @param ImageProvider $imageProvider
     */
    public function __construct(
        GuardianManager $guardianManager,
        ImageManager $imageManager,
        ImageProvider $imageProvider
    )
    {
        parent::__construct();

        $this->guardianManager = $guardianManager;
        $this->imageManager = $imageManager;
        $this->imageProvider = $imageProvider;
        $this->uploadFilesValidation = new UploadImagesValidation()
;    }

    /**
     * @param array $data
     * @param int $guardianId
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     * @throws \App\Component\IO\Exception\BadBase64FileException
     * @throws \App\Component\IO\Exception\FileAlreadyExistsException
     * @throws \App\Component\IO\Exception\InvalidImageException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(array $data, int $guardianId): void
    {
        $validation = $this->uploadFilesValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }

        $guardian = $this->guardianManager->getById($guardianId);
        if (null === $guardian) {
            throw new ResourceNotFoundException();
        }
        $ioPolicy = $data[UploadImagesValidation::FIELD_POLICY];
        $overwrite = $ioPolicy[UploadImagesValidation::FIELD_POLICY_OVERWRITE];

        $files = $data[UploadImagesValidation::FIELD_IMAGES];

        foreach ($files as $file) {
            $path = $file[UploadImagesValidation::FIELD_IMAGE_PATH];
            $content = $this->decodeBase64Content($file[UploadImagesValidation::FIELD_IMAGE_CONTENT]);
            $text = $file[UploadImagesValidation::FIELD_IMAGE_TEXT];
            $this->uploadFile(
                $content,
                $this->imageProvider->buildUserAbsoluteImagePath($guardian->getUuid(), $path), $overwrite
            );
            $this->handleImageEntity($guardian, $path, $text, $overwrite);
        }
    }

    /**
     * @param Guardian $guardian
     * @param string $path
     * @param string $text
     * @param bool $overwrite
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handleImageEntity(Guardian $guardian, string $path, string $text, bool $overwrite): void
    {
        $relativePath = $this->imageProvider->buildUserRelativeImagePath($guardian->getUuid(), $path);
        if ($overwrite) {
            $image = $this->imageManager->getByPath($relativePath);
            if (null === $image) {
                $this->handleImageEntityCreateImage($relativePath, $text);
            } else {
                $this->handleImageEntityUpdateImage($image, $relativePath, $text);
            }
        } else {
            $this->handleImageEntityCreateImage($relativePath, $text);
        }
    }

    /**
     * @param string $path
     * @param string $text
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handleImageEntityCreateImage(string $path, string $text): void
    {
        $image = (new Image())
            ->setPath($path)
            ->setText($text)
            ->setType(Image::TYPE_USER);
        $this->imageManager->update($image, true);
    }

    /**
     * @param Image $image
     * @param string $path
     * @param string $text
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handleImageEntityUpdateImage(Image $image, string $path, string $text): void
    {
        $image
            ->setPath($path)
            ->setText($text)
            ->setType(Image::TYPE_USER);
        $this->imageManager->update($image, true);
    }
}
