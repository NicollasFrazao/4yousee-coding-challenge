<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Http\UploadedFile;

class VideoStoreRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_video_store_route(): void
    {
        $uploaded_file = new UploadedFile(public_path('examples/example.mp4'), 'example.mp4');
        $response = $this->post(route('videos.store'), ['video' => $uploaded_file], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $this->assertTrue(isset($response['success']) && $response['success']);
    }
}
