<?php

namespace App\Component\IO\Validation;

use Symfony\Component\Validator\Constraints as Assert;

class UploadFilesValidation extends UploadFileValidationBase
{
    public const FIELD_FILES = 'files';
    /**
     * UploadFilesValidation constructor.
     */
    public function __construct()
    {
        parent::__construct(
            new Assert\Collection(array_merge(
                [self::FIELD_FILES => new Assert\All(new Assert\Collection($this->getFileUploadProperty()))],
                $this->getPolicyProperty()
            ))
        );
    }
}
