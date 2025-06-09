<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Support\Facades\Redis;

use App\Models\Video;

class PublishMessageJob implements ShouldQueue
{
    use Queueable;

    public Video $video;

    /**
     * Create a new job instance.
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Redis::publish('videos.store', json_encode($this->video->toArray()));
    }
}
