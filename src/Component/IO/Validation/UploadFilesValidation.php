<?php

namespace App\Component\IO\Validation;

use App\Component\Common\Validation\BaseValidation;
use Symfony\Component\Validator\Constraints as Assert;

class UploadFilesValidation extends BaseValidation
{
    public const FILE_PATH_REGEX = '@^\w[\w-]*(\/\w[\w-]*)*(\.\w+)$@';
    public const FILES_MIN = 1;

    public const FIELD_FILE_PATH = 'path';
    public const FIELD_FILE_CONTENT = 'content';
    public const FIELD_FILES = 'files';
    public const FIELD_POLICY = 'policy';
    public const FIELD_POLICY_OVERWRITE = 'overwrite';

    /**
     * UploadFilesValidation constructor.
     */
    public function __construct()
    {
        parent::__construct(
            new Assert\Collection([
                self::FIELD_FILES => [
                    new Assert\Count(['min' => self::FILES_MIN]),
                    new Assert\All(new Assert\Collection([
                        self::FIELD_FILE_CONTENT => new Assert\Type(['type' => 'string']),
                        self::FIELD_FILE_PATH => new Assert\Regex(self::FILE_PATH_REGEX),
                    ]))
                ],
                self::FIELD_POLICY => new Assert\Collection([
                    self::FIELD_POLICY_OVERWRITE => new Assert\Type(['type' => 'bool']),
                ]),
            ])
        );
    }
}
