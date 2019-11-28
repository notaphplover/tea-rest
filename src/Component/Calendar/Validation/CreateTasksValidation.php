<?php

namespace App\Component\Calendar\Validation;

use App\Component\Common\Validation\BaseValidation;
use App\Component\Validation\Constraint\DateStringConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTasksValidation extends BaseValidation
{
    public const FIELD_HH_SS_PATTERN = '/^(([0-1][0-9])|(2[0-4])):([0-5][0-9])$/';
    public const FIELD_HH_SS_PATTERN_HOURS_GROUP = 1;
    public const FIELD_HH_SS_PATTERN_MINUTES_GROUP = 4;
    public const FIELD_DAY = 'day';
    public const FIELD_TASK_STEP_END = 'end';
    public const FIELD_TASK_STEP_START = 'start';
    public const FIELD_TASK_STEP_IMG = 'image';
    public const FIELD_TASK_STEP_TEXT = 'text';
    public const FIELD_TASK_STEPS = 'steps';
    public const FIELD_TASKS = 'tasks';

    public const TASK_STEPS_MIN = 1;
    public const TASKS_MIN = 1;

    public function __construct()
    {
        parent::__construct(new Assert\Collection([
            self::FIELD_DAY => [
                new DateStringConstraint(),
                new Assert\Type(['type' => ['string']]),
            ],
            self::FIELD_TASKS => [
                new Assert\Count(['min' => self::TASKS_MIN]),
                new Assert\All(
                    new Assert\Collection([
                        self::FIELD_TASK_STEP_END => new Assert\Regex(['pattern' => self::FIELD_HH_SS_PATTERN]),
                        self::FIELD_TASK_STEPS => [
                            new Assert\Count(['min' => self::TASK_STEPS_MIN]),
                            new Assert\All(
                                new Assert\Collection([
                                    self::FIELD_TASK_STEP_IMG => new Assert\Url(),
                                    self::FIELD_TASK_STEP_TEXT => new Assert\Type(['type' => ['string']]),
                                ])
                            ),
                        ],
                        self::FIELD_TASK_STEP_START => new Assert\Regex(['pattern' => self::FIELD_HH_SS_PATTERN]),
                    ])
                )
            ]
        ]));
    }
}
