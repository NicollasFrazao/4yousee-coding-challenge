<?php

namespace App\Services;

use App\Services\Contracts\VideoStorageServiceInterface;

use Aws\S3\S3Client;

use Illuminate\Http\UploadedFile;

use App\Models\Video;

class S3Service implements VideoStorageServiceInterface
{
    private $client;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->client = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);    
    }

    public function store(UploadedFile $uploaded_file, Video $video) : bool
    {
        $filename = $uploaded_file->getClientOriginalName();
        $filename = pathinfo($filename, PATHINFO_FILENAME).'_'.time().'.'.pathinfo($filename, PATHINFO_EXTENSION);
        
        $response = $this->client->putObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => 'videos/'.$video->uuid.'/'.$filename,
            'SourceFile' => $uploaded_file,
            'ACL' => 'public-read',
            'ContentType' => $uploaded_file->getMimeType(),
        ]);

        if (isset($response['ObjectURL'])) 
        {
            $video->update(['url' => str_replace(env('AWS_ENDPOINT'), env('AWS_URL'), $response['ObjectURL'])]);
            return true;
        }
        else return false;
    }
}
