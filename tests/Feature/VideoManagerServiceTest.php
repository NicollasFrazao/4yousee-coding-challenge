<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use App\Models\Video;

class VideoManagerServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $videoManagerService;

    public function setUp() : void
    {
        parent::setUp();

        $this->videoManagerService = $this->app->make(\App\Services\Contracts\VideoManagerServiceInterface::class);
    }

    public function test_validate_if_uploaded_file_is_not_null() : void
    {
        $this->expectException(\TypeError::class);

        $uploaded_file = null;
        $this->videoManagerService->setUploadedFile($uploaded_file);
    }

    public function test_validate_if_uploaded_file_is_valid_video() : void
    {
        $this->expectException(\Exception::class);

        $uploaded_file = UploadedFile::fake()->image('fake.jpeg');
        $this->videoManagerService->setUploadedFile($uploaded_file);
    }

    public function test_validate_if_uploaded_file_is_less_than_or_equal_100MB() : void
    {
        $this->expectException(\Exception::class);

        $uploaded_file = UploadedFile::fake()->create('fake.mp4', 1000*200, 'video/mp4');
        $this->videoManagerService->setUploadedFile($uploaded_file);
    }

    public function test_get_original_name(): void
    {
        $uploaded_file = UploadedFile::fake()->create('sample.mp4', 1000*50, 'video/mp4');
        $this->videoManagerService->setUploadedFile($uploaded_file);

        $this->assertIsString($this->videoManagerService->getOriginalName());
    }

    public function test_get_duration(): void
    {
        $uploaded_file = new UploadedFile(public_path('examples/example.mp4'), 'example.mp4');
        $this->videoManagerService->setUploadedFile($uploaded_file);

        $this->assertIsString($this->videoManagerService->getDuration());
    }

    public function test_get_resolution(): void
    {
        $uploaded_file = new UploadedFile(public_path('examples/example.mp4'), 'example.mp4');
        $this->videoManagerService->setUploadedFile($uploaded_file);

        $this->assertIsString($this->videoManagerService->getResolution());
    }

    public function test_store(): void
    {
        $uploaded_file = new UploadedFile(public_path('examples/example.mp4'), 'example.mp4');
        $this->videoManagerService->setUploadedFile($uploaded_file);

        $video = Video::create(['uuid' => uniqid()]);
        $this->assertTrue($this->videoManagerService->store($video));
        
        $video->refresh();
        $this->assertTrue(isset($video->url));
    }
}
