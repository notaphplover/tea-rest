<?php

namespace App\Component\IO\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;

class FileAlreadyExistsException extends BaseHttpException
{
    private const ERROR_CODE = 6000;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            'The file already exists',
            self::ERROR_CODE,
            Response::HTTP_BAD_REQUEST,
            $previous
        );
    }

}
