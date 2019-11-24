<?php

namespace App\Component\Person\Exception;

use App\Component\Common\Exception\BaseHttpException;
use App\Entity\Guardian;
use App\Entity\Kid;
use Symfony\Component\HttpFoundation\Response;

class KidAssociationAlreadyExists extends BaseHttpException
{
    private const ERROR_CODE = 2002;

    public function __construct(Guardian $guardian, Kid $kid, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'The user "(%s)" is already associated to "%s".',
                $guardian->getUsername(),
                $kid->getNick()
            ),
            self::ERROR_CODE,
            Response::HTTP_BAD_REQUEST, $previous
        );
    }
}
