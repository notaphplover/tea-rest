<?php

namespace App\Component\IO\Handler;

use App\Component\Auth\Entity\TokenUser;
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
     * @param TokenUser $tokenUser
     * @return Image[]
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     * @throws \App\Component\IO\Exception\BadBase64FileException
     * @throws \App\Component\IO\Exception\FileAlreadyExistsException
     * @throws \App\Component\IO\Exception\InvalidImageException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(array $data, TokenUser $tokenUser): array
    {
        $validation = $this->uploadFilesValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }

        $guardian = $this->guardianManager->getById($tokenUser->getId());
        if (null === $guardian) {
            throw new ResourceNotFoundException();
        }

        $images = $data[UploadImagesValidation::FIELD_IMAGES];
        $imagesEntities = [];
        foreach ($images as $image) {
            $path = $image[UploadImagesValidation::FIELD_IMAGE_PATH];
            $content = $this->decodeBase64Content($image[UploadImagesValidation::FIELD_IMAGE_CONTENT]);
            $text = $image[UploadImagesValidation::FIELD_IMAGE_TEXT];
            $this->uploadFile(
                $content,
                $this->imagePathProvider->buildUserAbsoluteImagePath($tokenUser->getUuid(), $path),
                false
            );
            $imagesEntities[] = $this->handleImageEntity($tokenUser, $path, $text);
        }

        return $imagesEntities;
    }

    /**
     * @param TokenUser $tokenUser
     * @param string $path
     * @param string $text
     * @return Image
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handleImageEntity(TokenUser $tokenUser, string $path, string $text): Image
    {
        $scope = $this->imagePathProvider->buildUserScope($tokenUser->getUuid());
        return $this->handleImageEntityCreateImage($path, $scope, $text);
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
}
