<?php

namespace App\Adapter\Helper;

use Core\Port\Helper\StorageInterface;
use Illuminate\Support\Facades\Storage;

class StorageHelper implements StorageInterface
{
    const FILE_DISK = 'ged';

    /**
     * {@inheritDoc}
     */
    public function put(string $filename, string $fileContent): bool
    {
        return Storage::disk(self::FILE_DISK)->put($filename, $fileContent);
    }

    /**
     * {@inheritDoc}
     */
    public function getFileSize(string $filename): int
    {
        return Storage::disk(self::FILE_DISK)->size($filename);
    }

    /**
     * {@inheritDoc}
     */
    public function getFilePath(string $filename): string
    {
        return Storage::disk(self::FILE_DISK)->path($filename);
    }
}
