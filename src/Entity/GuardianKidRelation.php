<?php

namespace App\Entity;

use App\Component\Person\Repository\GuardianKidRelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuardianKidRelationRepository::class)
 * @ORM\Table(
 *     name="guardian_kid_relation",
 *     indexes={
 *          @ORM\Index(name="guardian_kid_relation_guardian", columns={"guardian_id"}),
 *          @ORM\Index(name="guardian_kid_relation_kid", columns={"kid_id"}),
 *     },
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(name="guardian_kid_unique",
 *            columns={"guardian_id", "kid_id"})
 *    }
 * )
 */
class GuardianKidRelation extends GuardianKidRelationBase
{
}
