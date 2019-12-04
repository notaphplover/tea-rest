<?php

namespace App\Component\IO\Handler;

use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\IO\Validation\UploadFilesValidation;
use App\Component\Person\Service\GuardianManager;
use App\Component\Validation\Exception\InvalidInputException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class UploadImagesHandler extends BaseUploadImageHandler
{

    /**
     * @var UploadFilesValidation
     */
    protected $uploadFilesValidation;

    /**
     * UploadImageHandler constructor.
     * @param ContainerBagInterface $containerBag
     * @param GuardianManager $guardianManager
     */
    public function __construct(ContainerBagInterface $containerBag, GuardianManager $guardianManager)
    {
        parent::__construct($containerBag, $guardianManager);

        $this->uploadFilesValidation = new UploadFilesValidation()
;    }

    /**
     * @param array $data
     * @param int $guardianId
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     * @throws \App\Component\IO\Exception\BadBase64FileException
     * @throws \App\Component\IO\Exception\FileAlreadyExistsException
     * @throws \App\Component\IO\Exception\InvalidImageException
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
        $ioPolicy = $data[UploadFilesValidation::FIELD_POLICY];
        $overwrite = $ioPolicy[UploadFilesValidation::FIELD_POLICY_OVERWRITE];

        $files = $data[UploadFilesValidation::FIELD_FILES];

        foreach ($files as $file) {
            $path = $file[UploadFilesValidation::FIELD_FILE_PATH];
            $content = $this->decodeBase64Content($file[UploadFilesValidation::FIELD_FILE_CONTENT]);

            $this->uploadFile($content, $this->usersFolder . $path, $overwrite);
        }
    }
}
