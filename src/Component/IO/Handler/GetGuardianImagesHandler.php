<?php


namespace App\Component\IO\Handler;


use App\Component\Auth\Entity\TokenUser;
use App\Component\IO\Service\ImageManager;
use App\Component\IO\Validation\GetGuardianImagesValidation;
use App\Component\Validation\Exception\InvalidInputException;
use App\Entity\Image;

class GetGuardianImagesHandler
{
    /**
     * @var GetGuardianImagesValidation
     */
    private $getGuardianImagesValidation;

    /**
     * @var ImageManager
     */
    private $imageManager;

    public function __construct(
        GetGuardianImagesValidation $getGuardianImagesValidation,
        ImageManager $imageManager
    )
    {
        $this->getGuardianImagesValidation = $getGuardianImagesValidation;
        $this->imageManager = $imageManager;
    }

    /**
     * @param array $data
     * @param TokenUser $tokenUser
     * @return Image[]
     * @throws InvalidInputException
     */
    public function handle(array $data, TokenUser $tokenUser): array
    {
        $validation = $this->getGuardianImagesValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }
        $pageNumber = $data[GetGuardianImagesValidation::FIELD_PAGE_NUMBER];
        $pageSize = $data[GetGuardianImagesValidation::FIELD_PAGE_SIZE];
        return $this->imageManager->getByGuardianAndPage($tokenUser->getId(), $pageNumber, $pageSize);
    }
}
