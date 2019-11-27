<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class TaskFragmentBase
{
    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $imgUrl;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $text;

    /**
     * @return string
     */
    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $imgUrl
     * @return $this
     */
    public function setImgUrl(string $imgUrl): TaskFragmentBase
    {
        $this->imgUrl = $imgUrl;
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText(string $text): TaskFragmentBase
    {
        $this->text = $text;
        return $this;
    }
}
