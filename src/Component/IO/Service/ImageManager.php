<?php

namespace App\Component\IO\Service;

use App\Component\Common\Service\BaseManager;
use App\Repository\ImageRepository;

class ImageManager extends BaseManager
{
    public function __construct(ImageRepository $entityRepository)
    {
        parent::__construct($entityRepository);
    }
}
