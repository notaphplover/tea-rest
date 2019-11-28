<?php

namespace App\Component\Calendar\Exception;

use App\Component\Common\Exception\BaseHttpException;
use App\Entity\TaskBase;
use Symfony\Component\HttpFoundation\Response;

class TaskInvalidIntervalException extends BaseHttpException
{
    private const ERROR_CODE = 5001;

    public function __construct(TaskBase $task, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Task found starting at %s and ending at %s. The end time must be greater than the start time',
                $task->getTimeStart()->format('H:i:s'),
                $task->getTimeEnd()->format('H:i:s')
            ),
            self::ERROR_CODE,
            Response::HTTP_BAD_REQUEST, $previous
        );
    }
}
