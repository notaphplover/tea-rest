<?php

namespace App\Entity;

use App\Repository\GuardianKidRelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuardianKidRelationRepository::class)
 * @ORM\Table(
 *     name="guardian_kid_relation",
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(name="guardian_kid_unique",
 *            columns={"guardian_id", "kid_id"})
 *    }
 * )
 */
class GuardianKidRelation extends GuardianKidRelationBase
{
}
