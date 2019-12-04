<?php

namespace App\Component\IO\Validation;

use Symfony\Component\Validator\Constraints as Assert;

class UploadFileValidation extends UploadFileValidationBase
{
    /**
     * UploadFileValidation constructor.
     */
    public function __construct()
    {
        parent::__construct(new Assert\Collection(
            array_merge($this->getFileUploadProperty(), $this->getPolicyProperty())
        ));
    }
}
