<?php

namespace App\Services\Contracts;

use Illuminate\Http\UploadedFile;

use App\Models\Video;

interface VideoManagerServiceInterface
{
    public function setUploadedFile(UploadedFile $uploaded_file): bool;
    public function getOriginalName() : string;
    public function getDuration() : string;
    public function getResolution(): string;
    public function store(Video $video) : bool;
}
