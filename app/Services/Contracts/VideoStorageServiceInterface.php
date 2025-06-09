<?php

namespace App\Services\Contracts;

use Illuminate\Http\UploadedFile;

use App\Models\Video;

interface VideoStorageServiceInterface
{
    public function store(UploadedFile $uploaded_file, Video $video) : bool;
}
