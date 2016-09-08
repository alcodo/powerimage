<?php

namespace Alcodo\PowerImage\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DeleteImage implements SelfHandling
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
        // get directory path from file
        $goalDirectory = pathinfo($this->path, PATHINFO_DIRNAME);

        // get all subdirectories
        $directories = Storage::disk('powerimage')->allDirectories($goalDirectory);

        // delete resized iamges
        foreach ($directories as $directory) {
            if ($goalDirectory == '.') {
                // root directory
                $checkPath = $directory.'/'.$this->path;
            } else {
                // subdirectory
                $checkPath = str_replace($goalDirectory, $directory, $this->path);
            }

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
