<?php

namespace App\Component\IO\Handler;

use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\IO\Validation\UploadFileValidation;
use App\Component\Person\Service\GuardianManager;
use App\Component\Validation\Exception\InvalidInputException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class UploadImageHandler extends BaseUploadImage
{
    /**
     * @var GuardianManager
     */
    protected $guardianManager;

    /**
     * @var UploadFileValidation
     */
    protected $uploadFileValidation;

    /**
     * @var
     */
    protected $usersFolder;

    /**
     * UploadImageHandler constructor.
     * @param ContainerBagInterface $containerBag
     * @param GuardianManager $guardianManager
     */
    public function __construct(
        ContainerBagInterface $containerBag,
        GuardianManager $guardianManager
    )
    {
        parent::__construct();

        $this->guardianManager = $guardianManager;
        $this->uploadFileValidation = new UploadFileValidation();
        $this->usersFolder = $containerBag->get('images_user_path');
    }

    /**
     * @param array $data
     * @param int $guardianId
     * @throws InvalidInputException
     * @throws \App\Component\IO\Exception\BadBase64FileException
     * @throws \App\Component\IO\Exception\FileAlreadyExistsException
     * @throws \App\Component\IO\Exception\InvalidImageException
     * @throws ResourceNotFoundException
     */
    public function handle(array $data, int $guardianId): void
    {
        $validation = $this->uploadFileValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }

        $guardian = $this->guardianManager->getById($guardianId);
        if (null === $guardian) {
            throw new ResourceNotFoundException();
        }
        $ioPolicy = $data[UploadFileValidation::FIELD_POLICY];
        $overwrite = $ioPolicy[UploadFileValidation::FIELD_POLICY_OVERWRITE];

        $file = $data[UploadFileValidation::FIELD_FILE];
        $path = $file[UploadFileValidation::FIELD_FILE_PATH];
        $content = $this->decodeBase64Content($file[UploadFileValidation::FIELD_FILE_CONTENT]);

        $this->uploadFile($content, $this->usersFolder . $path, $overwrite);
    }
}
