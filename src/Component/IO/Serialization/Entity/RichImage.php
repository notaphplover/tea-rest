<?php

namespace App\Component\IO\Serialization\Entity;

use App\Entity\Image;

class RichImage
{
    /**
     * @var Image
     */
    private $image;

    /**
     * @var ImageThumbnails
     */
    private $thumbnails;

    /**
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @return ImageThumbnails
     */
    public function getThumbnails(): ImageThumbnails
    {
        return $this->thumbnails;
    }

    /**
     * @param Image $image
     * @return RichImage
     */
    public function setImage(Image $image): RichImage
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @param ImageThumbnails $thumbnails
     * @return RichImage
     */
    public function setThumbnails(ImageThumbnails $thumbnails): RichImage
    {
        $this->thumbnails = $thumbnails;
        return $this;
    }
}
