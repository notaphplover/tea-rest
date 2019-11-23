<?php

namespace App\Component\Person\Exception;

use App\Component\Common\Exception\BaseHttpException;
use App\Entity\Kid;
use Symfony\Component\HttpFoundation\Response;

class KidAlreadyExistsException extends BaseHttpException
{
    private const ERROR_CODE = 2000;

    public function __construct(Kid $kid, \Throwable $previous = null) {
        parent::__construct(
            sprintf(
                'A kid is already associated to the nick provided (%s). Please choose a different one',
                $kid->getNick()
            ),
            self::ERROR_CODE,
            Response::HTTP_BAD_REQUEST, $previous
        );
    }
}
