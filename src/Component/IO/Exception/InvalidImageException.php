<?php

namespace App\Component\IO\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;

class InvalidImageException extends BaseHttpException
{
    private const ERROR_CODE = 6002;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            'Invalid image.',
            self::ERROR_CODE,
            Response::HTTP_BAD_REQUEST,
            $previous
        );
    }
}
