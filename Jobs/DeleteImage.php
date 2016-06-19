<?php

namespace Alcodo\PowerImage\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DeleteImage extends Job implements SelfHandling
{
    protected $path;

    /**
     * Create a new job instance.
     *
     * @param UploadedFile $image
     * @param null $filename
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cacheImagePath = str_replace(CreateImage::UploadDirectory, CreateImage::UploadDirectory.'.cache/', $this->path).'/';

        // delete glide cache
        if (Storage::exists($cacheImagePath)) {
            Storage::deleteDirectory($cacheImagePath);
        }

        // delete original image
        if (Storage::exists($this->path)) {
            Storage::delete($this->path);

            return true;
        }

        return false;
    }
}
