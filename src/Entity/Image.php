<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @ORM\Table(
 *     indexes={
 *          @ORM\Index(name="image_path", columns={"path"}),
 *          @ORM\Index(name="image_scope", columns={"scope"}),
 *     },
 *     name="image",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="image_path_scope",
 *              columns={"path", "scope"}
 *          )
 *     }
 * )
 */
class Image
{
    public const TYPE_COMMON = 'common';
    public const TYPE_USER = 'user';

    /**
     * @ORM\ManyToOne(targetEntity=Guardian::class)
     * @ORM\JoinColumn(nullable=true)
     * @var Guardian
     */
    private $guardian;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var null|int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=60)
     * @var string
     */
    private $scope;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=16)
     * @var string
     */
    private $type;

    /**
     * @return Guardian
     */
    public function getGuardian(): Guardian
    {
        return $this->guardian;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->scope . $this->path;
    }

    /**
     * @param Guardian $guardian
     * @return $this
     */
    public function setGuardian(Guardian $guardian): Image
    {
        $this->guardian = $guardian;
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): Image
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $scope
     * @return $this
     */
    public function setScope(string $scope): Image
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText(string $text): Image
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): Image
    {
        $this->type = $type;
        return $this;
    }
}
