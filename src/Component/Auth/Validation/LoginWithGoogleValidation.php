<?php

namespace App\Component\Auth\Validation;

use App\Component\Common\Validation\BaseValidation;
use Symfony\Component\Validator\Constraints as Assert;

class LoginWithGoogleValidation extends BaseValidation
{
    public const FIELD_TOKEN = 'token';

    public function __construct()
    {
        parent::__construct(new Assert\Collection([self::FIELD_TOKEN => new Assert\Type(['type' => 'string'])]));
    }
}
