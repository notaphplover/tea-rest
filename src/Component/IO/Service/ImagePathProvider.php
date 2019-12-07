<?php


namespace App\Component\IO\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class ImagePathProvider
{
    /**
     * @var string
     */
    protected $projectFolder;

    /**
     * @var string
     */
    protected $publicFolder;

    /**
     * @var string
     */
    protected $usersFolder;

    /**
     * ImageProvider constructor.
     * @param ContainerBagInterface $containerBag
     */
    public function __construct(ContainerBagInterface $containerBag)
    {
        $this->projectFolder = $containerBag->get('kernel.project_dir');
        $this->publicFolder = $containerBag->get('public_folder');
        $this->usersFolder = $containerBag->get('images_user_path');
    }

    /**
     * @param string $userFolder
     * @param string $path
     * @return string
     */
    public function buildUserAbsoluteImagePath(string $userFolder, string $path): string
    {
        return $this->projectFolder
            . DIRECTORY_SEPARATOR
            . $this->publicFolder
            . $this->buildUserScope($userFolder)
            . $path;
    }

    public function buildUserScope(string $userFolder): string
    {
        return $this->usersFolder . $userFolder . DIRECTORY_SEPARATOR;
    }
}
