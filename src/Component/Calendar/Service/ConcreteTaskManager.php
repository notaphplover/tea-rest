<?php

namespace App\Component\Calendar\Service;

use App\Component\Calendar\Repository\ConcreteTaskRepository;

class ConcreteTaskManager extends TaskBaseManager
{
    public function __construct(ConcreteTaskRepository $entityRepository)
    {
        parent::__construct($entityRepository);
    }
}
