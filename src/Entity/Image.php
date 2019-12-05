<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @ORM\Table(name="image")
 */
class Image
{
    public const TYPE_COMMON = 'common';
    public const TYPE_USER = 'user';

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
     * @return int
     */
    public function getId(): int
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
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): Image
    {
        $this->path = $path;
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
