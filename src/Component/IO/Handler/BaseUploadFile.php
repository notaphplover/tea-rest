<?php


namespace App\Component\IO\Handler;

use App\Component\IO\Exception\BadBase64FileException;
use App\Component\IO\Exception\FileAlreadyExistsException;
use Symfony\Component\Filesystem\Filesystem;

abstract class BaseUploadFile
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * @param string $content
     * @param string $path
     * @param bool $overwrite
     * @throws FileAlreadyExistsException
     */
    protected function uploadFile(string $content, string $path, bool $overwrite): void
    {
        if (!$overwrite && $this->filesystem->exists($path)) {
            throw new FileAlreadyExistsException();
        }
        $this->filesystem->dumpFile($path, $content);
    }

    /**
     * @param $base64Content
     * @return string
     * @throws BadBase64FileException
     */
    protected function decodeBase64Content($base64Content): string
    {
        try {
            return base64_decode($base64Content, true);
        } catch (\Exception $exception) {
            throw new BadBase64FileException($exception);
        }
    }
}
