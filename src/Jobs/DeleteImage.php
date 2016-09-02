<?php

namespace Alcodo\PowerImage\Jobs;

use App\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DeleteImage implements SelfHandling
{
    use Queueable;

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
        // TODO
        $imageCachePath = $this->getImageCachePath();

        // delete glide cache
        if (Storage::exists($imageCachePath)) {
            Storage::deleteDirectory($imageCachePath);
        }

        // delete original image
        if (Storage::exists($this->path)) {
            Storage::delete($this->path);

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getImageCachePath()
    {
        $cacheImagePath = str_replace(
            CreateImage::UploadDirectory,
            CreateImage::UploadDirectory.'/.cache',
            $this->path
        );

        return $cacheImagePath.'/';
    }
}
