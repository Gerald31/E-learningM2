<?php

namespace Core\Port\Framework\File;

interface UploadedFileInterface
{
    /**
     * Returns the original file name.
     */
    public function getClientOriginalName();

    /**
     * Gets file size
     */
    public function getSize();

    /**
     * Returns the original file extension.
     */
    public function getClientOriginalExtension();

    /**
     * Moves the file to a new location.
     */
    public function move(string $directory, string $name = null);
}
