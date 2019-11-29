<?php

namespace App\Component\Calendar\Service;

use App\Repository\ConcreteSubTaskRepository;

class ConcreteSubTaskManager extends TaskFragmentBaseManager
{
    public function __construct(ConcreteSubTaskRepository $entityRepository)
    {
        parent::__construct($entityRepository);
    }
}
