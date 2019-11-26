<?php

namespace App\Component\Common\Exception;

use Symfony\Component\HttpFoundation\Response;

class AccessDeniedException extends BaseHttpException
{
    private const ERROR_CODE = 4002;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            'Access denied',
            self::ERROR_CODE,
            Response::HTTP_FORBIDDEN,
            $previous
        );
    }
}
