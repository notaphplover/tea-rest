<?php

namespace App\Component\IO\Handler;

use App\Component\Person\Service\GuardianManager;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

abstract class BaseUploadImageHandler extends BaseUploadImage
{
    /**
     * @var GuardianManager
     */
    protected $guardianManager;

    /**
     * @var string
     */
    protected $usersFolder;

    /**
     * UploadImageHandler constructor.
     * @param ContainerBagInterface $containerBag
     * @param GuardianManager $guardianManager
     */
    public function __construct(
        ContainerBagInterface $containerBag,
        GuardianManager $guardianManager
    )
    {
        parent::__construct();

        $this->guardianManager = $guardianManager;
        $this->usersFolder = $containerBag->get('images_user_path');
    }

    /**
     * @param array $data
     * @param int $guardianId
     */
    public abstract function handle(array $data, int $guardianId): void;
}
