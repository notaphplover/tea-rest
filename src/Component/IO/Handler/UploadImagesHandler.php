<?php

namespace App\Component\IO\Handler;

use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\IO\Service\ImageManager;
use App\Component\IO\Service\ImagePathProvider;
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
     * @var ImagePathProvider
     */
    protected $imagePathProvider;

    /**
     * @var UploadImagesValidation
     */
    protected $uploadFilesValidation;

    /**
     * UploadImageHandler constructor.
     * @param GuardianManager $guardianManager
     * @param ImageManager $imageManager
     * @param ImagePathProvider $imagePathProvider
     */
    public function __construct(
        GuardianManager $guardianManager,
        ImageManager $imageManager,
        ImagePathProvider $imagePathProvider
    )
    {
        parent::__construct();

        $this->guardianManager = $guardianManager;
        $this->imageManager = $imageManager;
        $this->imagePathProvider = $imagePathProvider;
        $this->uploadFilesValidation = new UploadImagesValidation()
;    }

    /**
     * @param array $data
     * @param int $guardianId
     * @return Image[]
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     * @throws \App\Component\IO\Exception\BadBase64FileException
     * @throws \App\Component\IO\Exception\FileAlreadyExistsException
     * @throws \App\Component\IO\Exception\InvalidImageException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(array $data, int $guardianId): array
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
        $images = [];
        foreach ($files as $file) {
            $path = $file[UploadImagesValidation::FIELD_IMAGE_PATH];
            $content = $this->decodeBase64Content($file[UploadImagesValidation::FIELD_IMAGE_CONTENT]);
            $text = $file[UploadImagesValidation::FIELD_IMAGE_TEXT];
            $this->uploadFile(
                $content,
                $this->imagePathProvider->buildUserAbsoluteImagePath($guardian->getUuid(), $path), $overwrite
            );
            $images[] = $this->handleImageEntity($guardian, $path, $text, $overwrite);
        }

        return $images;
    }

    /**
     * @param Guardian $guardian
     * @param string $path
     * @param string $text
     * @param bool $overwrite
     * @return Image
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handleImageEntity(Guardian $guardian, string $path, string $text, bool $overwrite): Image
    {
        $scope = $this->imagePathProvider->buildUserScope($guardian->getUuid());
        if (!$overwrite) {
            return $this->handleImageEntityCreateImage($path, $scope, $text);
        }

        $image = $this->imageManager->getByPathAndScope($path, $scope);

        if (null === $image) {
            return $this->handleImageEntityCreateImage($path, $scope, $text);
        }

        $this->handleImageEntityUpdateImage($image, $path, $scope, $text);
        return $image;
    }

    /**
     * @param string $path
     * @param string $scope
     * @param string $text
     * @return Image
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handleImageEntityCreateImage(string $path, string $scope, string $text): Image
    {
        $image = (new Image())
            ->setPath($path)
            ->setScope($scope)
            ->setText($text)
            ->setType(Image::TYPE_USER);
        $this->imageManager->update($image, true);
        return $image;
    }

    /**
     * @param Image $image
     * @param string $path
     * @param string $scope
     * @param string $text
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handleImageEntityUpdateImage(Image $image, string $path, string $scope, string $text): void
    {
        $image
            ->setPath($path)
            ->setScope($scope)
            ->setText($text)
            ->setType(Image::TYPE_USER);
        $this->imageManager->update($image, true);
    }
}
