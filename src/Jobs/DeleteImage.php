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
        $directories = Storage::disk('powerimage')->allDirectories();

        // delete resized iamges
        foreach ($directories as $directory) {
            $checkPath = $directory . '/' . $this->path;
            if (Storage::disk('powerimage')->exists($checkPath)) {
                Storage::disk('powerimage')->delete($checkPath);
            }
        }

        // delete original image
        if (Storage::disk('powerimage')->exists($this->path)) {
            Storage::disk('powerimage')->delete($this->path);

            return true;
        }

        return false;
    }
}
