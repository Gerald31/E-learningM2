<?php

namespace App\Adapter\Framework;

use Core\Port\Framework\File\UploadedFileInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Wrapper class for \Illuminate\Http\UploadedFile implementing UploadedFileInterface from Core.
 *
 * @see UploadedFile::createFromBase() to get an instance of this from Laravel one
 */
class UploadedFile extends \Illuminate\Http\UploadedFile implements UploadedFileInterface
{
    /**
     * Remove is_writable() check because of bug on Windows.
     * TODO: remove this rewrite when app finally gets a real server in production
     *
     * @param string $directory
     * @param string|null $name
     * @return \Symfony\Component\HttpFoundation\File\File
     *
     * @see https://bugs.php.net/bug.php?id=68926
     */
    protected function getTargetFile(string $directory, string $name = null)
    {
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new FileException(sprintf('Unable to create the "%s" directory.', $directory));
            }
        }

        $target = rtrim($directory, '/\\') .
            \DIRECTORY_SEPARATOR .
            (null === $name ? $this->getBasename() : $this->getName($name));

        return new File($target, false);
    }
}
