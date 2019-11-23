<?php

namespace App\Entity;

use App\Repository\GuardianKidRelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuardianKidRelationRepository::class)
 * @ORM\Table(name="guardian_kid_relation")
 */
class GuardianKidRelation extends GuardianKidRelationBase
{
}
