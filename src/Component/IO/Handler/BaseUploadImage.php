<?php

namespace App\Component\IO\Handler;

use App\Component\IO\Exception\InvalidImageException;

/**
 * This class provides the logic to upload an image file.
 *
 * Thi
 *
 * Class BaseUploadImage
 * @package App\Component\IO\Service
 */
abstract class BaseUploadImage extends BaseUploadFile
{
    /**
     * @param string $content
     * @param string $path
     * @param bool $overwrite
     * @throws \App\Component\IO\Exception\FileAlreadyExistsException
     * @throws InvalidImageException
     */
    protected function uploadFile(string $content, string $path, bool $overwrite): void
    {
        $this->validateImage($content, $path);
        parent::uploadFile($content, $path, $overwrite);
    }

    /**
     * Checks if a path to a file has a valid jpg extension.
     * @param string $path Path to a JPG file.
     * @return bool True if the path has a valid extension.
     */
    private function hasJPGExtension(string $path): bool
    {
        $extensionIndex = strrpos($path, '.');
        if (false === $extensionIndex) {
            return false;
        }
        $extension = strtolower(substr($path, $extensionIndex));
        return '.jpg' === $extension || '.jpeg' === $extension;
    }

    /**
     * Checks whether an image has a valid JPG header
     * @param string $content Image content to analyze. The first four bytes are enough
     * @return bool
     */
    private function hasJPGHeader(string $content): bool
    {
        $length = strlen($content);
        if ($length < 4) {
            return false;
        }
        $firstSubstr = substr($content, 0, 2);
        $secondSubstr = substr($content, 2, 2);
        $soi = unpack('n', $firstSubstr)[1];
        $marker = unpack('n', $secondSubstr)[1];
        return 0xFFD8 == $soi && ($marker & 0xFF00) === 0xFF00;
    }

    /**
     * @param string $content
     * @param string $path
     * @return bool
     */
    private function isJPG(string $content, string $path): bool
    {
        return $this->hasJPGExtension($path) && $this->hasJPGHeader($content);
    }

    /**
     * @param string $content
     * @param string $path
     * @throws InvalidImageException
     */
    private function validateImage(string $content, string $path): void
    {
        if (!$this->isJPG($content, $path)) {
            throw new InvalidImageException();
        }
    }
}
