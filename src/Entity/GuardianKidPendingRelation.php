<?php

namespace App\Entity;

use App\Repository\GuardianKidPendingRelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuardianKidPendingRelationRepository::class)
 * @ORM\Table(
 *     name="guardian_kid_pending_relation",
 *     indexes={
 *          @ORM\Index(name="guardian_kid_pending_relation_guardian", columns={"guardian_id"}),
 *          @ORM\Index(name="guardian_kid_pending_relation_kid", columns={"kid_id"}),
 *     },
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(name="guardian_kid_unique",
 *            columns={"guardian_id", "kid_id"})
 *    }
 * )
 *
 * Class GuardianKidPendingRelation
 * @package App\Entity
 */
class GuardianKidPendingRelation extends GuardianKidRelationBase
{
}
