<?php

namespace App\Component\IO\Service;

use App\Component\IO\Serialization\Entity\ImageThumbnails;
use App\Component\IO\Serialization\Entity\RichImage;
use App\Entity\Image;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class RichImageGenerator
{
    private const FILTER_SM = 'squaresm';

    private const FILTER_MD = 'squaremd';

    private const FILTER_LG = 'squarelg';

    /**
     * @var CacheManager
     */
    private $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param Image $image
     * @return RichImage
     */
    public function buildRichImage(Image $image): RichImage
    {
        $thumbnails = (new ImageThumbnails())
            ->setSm($this->cacheManager->getBrowserPath($image->getUrl(), self::FILTER_SM))
            ->setMd($this->cacheManager->getBrowserPath($image->getUrl(), self::FILTER_MD))
            ->setLg($this->cacheManager->getBrowserPath($image->getUrl(), self::FILTER_LG))
        ;

        return (new RichImage())
            ->setImage($image)
            ->setThumbnails($thumbnails);
    }

    /**
     * @param Image[] $images
     * @return Image[]
     */
    public function buildRichImages(array $images): array
    {
        return array_map(function (Image $image) { return $this->buildRichImage($image); }, $images);
    }
}
