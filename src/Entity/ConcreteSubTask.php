<?php

namespace App\Entity;

use App\Repository\ConcreteSubTaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConcreteSubTaskRepository::class)
 * @ORM\Table(name="concrete_subtask")
 */
class ConcreteSubTask extends TaskFragmentBase
{
}
