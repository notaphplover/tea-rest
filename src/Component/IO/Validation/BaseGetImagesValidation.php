<?php

namespace App\Component\IO\Validation;

use App\Component\Common\Validation\BaseValidation;
use Symfony\Component\Validator\Constraints as Assert;

abstract class BaseGetImagesValidation extends BaseValidation
{
    public const FIELD_PAGE_NUMBER = 'page';
    public const FIELD_PAGE_NUMBER_MIN = 1;
    public const FIELD_PAGE_SIZE = 'size';
    public const FIELD_PAGE_SIZE_SM = 10;
    public const FIELD_PAGE_SIZE_MD = 20;
    public const FIELD_PAGE_SIZE_LG = 30;
    public const FIELD_PAGE_SIZE_XL = 40;

    public function __construct()
    {
        parent::__construct(new Assert\Collection([
            self::FIELD_PAGE_NUMBER => [
                new Assert\GreaterThanOrEqual(['value' => self::FIELD_PAGE_NUMBER_MIN]),
                new Assert\Type(['type' => 'int'])
            ],
            self::FIELD_PAGE_SIZE => [
                new Assert\Type(['type' => 'int']),
                new Assert\Choice([
                    self::FIELD_PAGE_SIZE_SM,
                    self::FIELD_PAGE_SIZE_MD,
                    self::FIELD_PAGE_SIZE_LG,
                    self::FIELD_PAGE_SIZE_XL
                ])
            ],
        ]));
    }
}
