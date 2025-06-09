<?php

namespace App\Services;

use App\Services\Contracts\VideoManagerServiceInterface;
use App\Services\Contracts\VideoStorageServiceInterface;

use FFMpeg\FFProbe;

use Illuminate\Http\UploadedFile;

use App\Models\Video;

class FFMpegService implements VideoManagerServiceInterface
{
    protected $videoStorageService;

    private UploadedFile $uploaded_file;

    /**
     * Create a new class instance.
     */
    public function __construct(VideoStorageServiceInterface $videoStorageService)
    {
        $this->videoStorageService = $videoStorageService;
    }

    private function validateUploadedFile() : bool
    {
        if (!$this->uploaded_file) throw new \Exception('O campo "Arquivo de Vídeo" é obrigatório!');
        else if (!in_array($this->uploaded_file->getMimeType(), ['video/mp4', 'video/quicktime', 'video/x-msvideo'])) throw new \Exception('O campo "Arquivo de Vídeo" deve conter um arquivo de vídeo!');
        else if ($this->uploaded_file->getSize()/1024/1000 > 100) throw new \Exception('O campo "Arquivo de Vídeo" deve conter um arquivo com tamanho máximo de 100MB!');
        else return true;
    }

    public function setUploadedFile(UploadedFile $uploaded_file): bool
    {
        $this->uploaded_file = $uploaded_file;
        return $this->validateUploadedFile();
    }

    public function getOriginalName() : string
    {
        $this->validateUploadedFile();
        return $this->uploaded_file->getClientOriginalName();
    }

    public function getDuration() : string
    {
        $this->validateUploadedFile();

        $ffprobe = FFProbe::create();
        $video_duration = \Carbon::createFromFormat('s', intval($ffprobe->format($this->uploaded_file)->get('duration')))->format('H:i:s');

        return $video_duration;
    }

    public function getResolution(): string
    {
        $this->validateUploadedFile();

        $ffprobe = FFProbe::create();
        $video_stream = $ffprobe->streams($this->uploaded_file)->videos()->first();
        $video_resolution = $video_stream->get('width').'x'.$video_stream->get('height');

        return $video_resolution;
    }

    public function store(Video $video) : bool
    {
        return $this->videoStorageService->store($this->uploaded_file, $video);
    }
}
