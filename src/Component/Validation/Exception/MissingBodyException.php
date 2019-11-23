<?php

namespace App\Component\Validation\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;

class MissingBodyException extends BaseHttpException
{
    private const ERROR_CODE = 3000;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            'A body was expected, but none was found',
            self::ERROR_CODE,
            Response::HTTP_BAD_REQUEST,
            $previous
        );
    }
}
