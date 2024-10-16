<?php

namespace Core\Port\Helper;

interface StorageInterface
{
    /**
     * Create a file on the disk from contents
     *
     * @param string $filename
     * @param string $fileContent
     * @return bool
     */
    public function put(string $filename, string $fileContent): bool;

    /**
     * @param string $filename
     * @return int
     */
    public function getFileSize(string $filename): int;

    /**
     * @param string $filename
     * @return string
     */
    public function getFilePath(string $filename): string;
}
