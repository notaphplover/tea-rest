<?php

namespace App\Component\Validation\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;

class InvalidJsonFormatException extends BaseHttpException
{
    private const ERROR_CODE = 3001;

    public function __construct(\Throwable $previous = null) {
        parent::__construct(
            'Invalid JSON input provided',
            self::ERROR_CODE,
            Response::HTTP_BAD_REQUEST,
            $previous
        );
    }
}
