<?php

namespace App\Component\IO\Serialization\Entity;

class ImageThumbnails
{
    /**
     * @var null|string
     */
    private $sm;
    /**
     * @var null|string
     */
    private $md;
    /**
     * @var null|string
     */
    private $lg;

    /**
     * @return string|null
     */
    public function getSm(): ?string
    {
        return $this->sm;
    }

    /**
     * @return string|null
     */
    public function getMd(): ?string
    {
        return $this->md;
    }

    /**
     * @return string|null
     */
    public function getLg(): ?string
    {
        return $this->lg;
    }

    /**
     * @param string|null $sm
     * @return ImageThumbnails
     */
    public function setSm(?string $sm): ImageThumbnails
    {
        $this->sm = $sm;
        return $this;
    }

    /**
     * @param string|null $md
     * @return ImageThumbnails
     */
    public function setMd(?string $md): ImageThumbnails
    {
        $this->md = $md;
        return $this;
    }

    /**
     * @param string|null $lg
     * @return ImageThumbnails
     */
    public function setLg(?string $lg): ImageThumbnails
    {
        $this->lg = $lg;
        return $this;
    }
}
