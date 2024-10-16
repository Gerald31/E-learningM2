<?php

namespace App\Services\Upload;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadServices
{
    /**
     * @param UploadedFile|null $file
     * @param string|null $folder
     * @param string|null $destionation
     * @return string
     */
    public function storeFile(?UploadedFile $file = null, ?string $folder = '/public/', ?string $destionation = 'uploads/'): ?string
    {
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::uuid() .'.'. $extension;
            $pathFile = "{$destionation}{$fileName}";
            $fileContent = file_get_contents($file->getRealPath());
            Storage::disk('local')->put("{$folder}{$pathFile}", $fileContent);
            return $pathFile;
        }
        return null;
    }

}
