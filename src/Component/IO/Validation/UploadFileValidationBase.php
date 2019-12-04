<?php

namespace App\Component\IO\Validation;

use App\Component\Common\Validation\BaseValidation;
use Symfony\Component\Validator\Constraints as Assert;

abstract class UploadFileValidationBase extends BaseValidation
{
    public const FILE_PATH_REGEX = '@^\w[\w-]*(\/\w[\w-]*)*$@';
    public const FIELD_FILE = 'file';
    public const FIELD_FILE_PATH = 'path';
    public const FIELD_FILE_CONTENT = 'content';
    public const FIELD_POLICY = 'policy';
    public const FIELD_POLICY_OVERWRITE = 'overwrite';

    /**
     * @return array
     */
    protected function getFileUploadProperty(): array
    {
        return [
            self::FIELD_FILE => new Assert\Collection([
                self::FIELD_FILE_CONTENT => new Assert\Type(['type' => 'string']),
                self::FIELD_FILE_PATH => new Assert\Regex(self::FILE_PATH_REGEX),
            ]),
        ];
    }

    /**
     * @return array
     */
    protected function getPolicyProperty(): array
    {
        return [
            self::FIELD_POLICY => new Assert\Collection([
                self::FIELD_POLICY_OVERWRITE => new Assert\Type(['type' => 'bool']),
            ]),
        ];
    }
}
