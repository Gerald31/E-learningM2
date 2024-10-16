<?php

namespace App\Adapter\Framework;

use Core\Port\Framework\File\UploadedFileInterface;
use Illuminate\Http\UploadedFile as BaseUploadedFile;

class UploadedFileFactory
{
    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @return \Core\Port\Framework\File\UploadedFileInterface
     */
    public function createFromBase(BaseUploadedFile $file): UploadedFileInterface
    {
        return UploadedFile::createFromBase($file);
    }
}
