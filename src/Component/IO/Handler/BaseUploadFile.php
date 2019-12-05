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
        /*
         * We must stay safe against script injection attacks.
         *
         * 1. Create a void file.
         * 2. Set read only permissions to the file.
         * 3. Dump the content to the file.
         *
         * With this implementation it's not possible to execute the script.
         * If the attacker attempts to execute the script before the chmod instruction, and empty file will be executed.
         * If the attacker attempts to execute the script after the chmod, it has read only permissions.
         */
        $this->filesystem->dumpFile($path, '');
        $this->filesystem->chmod($path, 0644);
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
