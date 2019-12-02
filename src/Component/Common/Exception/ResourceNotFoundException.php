<?php

namespace App\Component\Common\Exception;

use Symfony\Component\HttpFoundation\Response;

class ResourceNotFoundException extends BaseHttpException
{
    private const ERROR_CODE = 4000;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            'Resource not found',
            self::ERROR_CODE,
            Response::HTTP_NOT_FOUND,
            $previous
        );
    }
}
