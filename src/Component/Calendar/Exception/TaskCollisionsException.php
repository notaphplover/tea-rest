<?php

namespace App\Component\Calendar\Exception;

use App\Component\Common\Exception\BaseHttpException;
use App\Entity\TaskBase;
use Symfony\Component\HttpFoundation\Response;

class TaskCollisionsException extends BaseHttpException
{
    private const ERROR_CODE = 5000;

    public function __construct(TaskBase $task = null, \Throwable $previous = null)
    {
        $msg = null === $task ?
            'A collision was found when processing a task' :
            sprintf(
                'A collision was found when processing a task starting at %s and ending at %s.',
                $task->getTimeStart()->format('H:i:s'),
                $task->getTimeEnd()->format('H:i:s')
            )
        ;
        parent::__construct(
            $msg,
            self::ERROR_CODE,
            Response::HTTP_BAD_REQUEST, $previous
        );
    }
}
