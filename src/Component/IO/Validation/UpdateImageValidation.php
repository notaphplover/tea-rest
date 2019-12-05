<?php

namespace App\Component\IO\Validation;

use App\Component\Common\Validation\BaseValidation;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateImageValidation extends BaseValidation
{
    public const IMAGES_MAX_LENGTH = 2097152;
    public const IMAGE_PATH_REGEX = '@^\w[\w-]*(\/\w[\w-]*)*(\.\w+)$@';
    public const IMAGE_TEXT_MAX_LENGTH = 50;

    public const FIELD_IMAGE_CONTENT = 'content';
    public const FIELD_IMAGE_PATH = 'path';
    public const FIELD_IMAGE_TEXT = 'text';

    /**
     * UpdateImagesValidation constructor.
     */
    public function __construct()
    {
        parent::__construct(
            new Assert\Collection([
                self::FIELD_IMAGE_CONTENT => new Assert\Optional([
                    new Assert\Length(['max' => self::IMAGES_MAX_LENGTH]),
                    new Assert\Type(['type' => 'string'])
                ]),
                self::FIELD_IMAGE_PATH => new Assert\Optional(new Assert\Regex(self::IMAGE_PATH_REGEX)),
                self::FIELD_IMAGE_TEXT => new Assert\Optional([
                    new Assert\Length(['max' => self::IMAGE_TEXT_MAX_LENGTH]),
                    new Assert\Type(['type' => 'string'])
                ]),
            ])
        );
    }
}
