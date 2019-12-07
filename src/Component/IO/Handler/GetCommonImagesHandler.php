<?php

namespace App\Component\IO\Handler;

use App\Component\Auth\Entity\TokenUser;
use App\Component\IO\Service\ImageManager;
use App\Component\IO\Validation\GetCommonImagesValidation;
use App\Component\Validation\Exception\InvalidInputException;
use App\Entity\Image;

class GetCommonImagesHandler
{
    /**
     * @var GetCommonImagesValidation
     */
    private $getCommonImagesValidation;

    /**
     * @var ImageManager
     */
    private $imageManager;

    public function __construct(
        GetCommonImagesValidation $getCommonImagesValidation,
        ImageManager $imageManager
    )
    {
        $this->getCommonImagesValidation = $getCommonImagesValidation;
        $this->imageManager = $imageManager;
    }

    /**
     * @param array $data
     * @return Image[]
     * @throws InvalidInputException
     */
    public function handle(array $data): array
    {
        $validation = $this->getCommonImagesValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }
        $pageNumber = $data[GetCommonImagesValidation::FIELD_PAGE_NUMBER];
        $pageSize = $data[GetCommonImagesValidation::FIELD_PAGE_SIZE];
        return $this->imageManager->getCommonImagesByPage($pageNumber, $pageSize);
    }
}
