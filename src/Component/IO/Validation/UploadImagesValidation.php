<?php

namespace App\Component\IO\Validation;

use App\Component\Common\Validation\BaseValidation;
use Symfony\Component\Validator\Constraints as Assert;

class UploadImagesValidation extends BaseValidation
{
    public const IMAGES_MAX_LENGTH = 2097152;
    public const IMAGE_PATH_REGEX = '@^\w[\w-]*(\/\w[\w-]*)*(\.\w+)$@';
    public const IMAGE_TEXT_MAX_LENGTH = 50;
    public const IMAGES_MIN = 1;

    public const FIELD_IMAGE_PATH = 'path';
    public const FIELD_IMAGE_CONTENT = 'content';
    public const FIELD_IMAGE_TEXT = 'text';
    public const FIELD_IMAGES = 'images';

    /**
     * UploadFilesValidation constructor.
     */
    public function __construct()
    {
        parent::__construct(
            new Assert\Collection([
                self::FIELD_IMAGES => [
                    new Assert\Count(['min' => self::IMAGES_MIN]),
                    new Assert\All(new Assert\Collection([
                        self::FIELD_IMAGE_CONTENT => [
                            new Assert\Length(['max' => self::IMAGES_MAX_LENGTH]),
                            new Assert\Type(['type' => 'string'])
                        ],
                        self::FIELD_IMAGE_PATH => new Assert\Regex(self::IMAGE_PATH_REGEX),
                        self::FIELD_IMAGE_TEXT => [
                            new Assert\Length(['max' => self::IMAGE_TEXT_MAX_LENGTH]),
                            new Assert\Type(['type' => 'string'])
                        ],
                    ]))
                ],
            ])
        );
    }
}
