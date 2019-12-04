<?php

namespace App\Component\IO\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;

class BadBase64FileException extends BaseHttpException
{
    private const ERROR_CODE = 6001;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            'The file content is not a valid Base64 content',
            self::ERROR_CODE,
            Response::HTTP_BAD_REQUEST,
            $previous
        );
    }
}
