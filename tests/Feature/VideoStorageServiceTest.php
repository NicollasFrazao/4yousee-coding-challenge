<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use App\Models\Video;

class VideoStorageServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $videoManagerService;

    public function setUp() : void
    {
        parent::setUp();

        $this->videoStorageService = $this->app->make(\App\Services\Contracts\VideoStorageServiceInterface::class);
    }

    public function test_store(): void
    {
        $uploaded_file = new UploadedFile(public_path('examples/example.mp4'), 'example.mp4');
        
        $video = Video::create(['uuid' => uniqid()]);
        $this->assertTrue($this->videoStorageService->store($uploaded_file, $video));
        
        $video->refresh();
        $this->assertTrue(isset($video->url));
    }
}
