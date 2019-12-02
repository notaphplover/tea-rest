<?php

namespace App\Component\Calendar\Validation;

use App\Component\Common\Validation\BaseValidation;
use App\Component\Validation\Constraint\DateStringConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class GetTasksValidation extends BaseValidation
{
    public const FIELD_DAY = 'day';

    public function __construct()
    {
        parent::__construct(new Assert\Collection([
            self::FIELD_DAY => [new DateStringConstraint(), new Assert\Type(['type' => ['string']])],
        ]));
    }
}
