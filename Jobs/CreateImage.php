<?php

namespace Alcodo\PowerImage\Jobs;

use App\Jobs\Job;
use Cocur\Slugify\Slugify;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateImage extends Job implements SelfHandling
{

    const UploadDirectory = 'uploads/images/';

    protected $image;
    protected $filename;
    protected $extension;

    /**
     * Create a new job instance.
     *
     * @param UploadedFile $image
     * @param null $filename
     */
    public function __construct(UploadedFile $image, $filename = null)
    {
        $this->image = $image;
        $this->extension = $this->image->getClientOriginalExtension();
        $this->filename = $this->getFilename($filename);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filename = $this->getCompleteFilename();
        $absoulteFilename = self::UploadDirectory . $filename;

        // save
        Storage::put($absoulteFilename, File::get($this->image));
        return $absoulteFilename;
    }

    /**
     * Generates a filename and check that file is not exists
     *
     * @param int $i
     * @return string
     */
    private function getCompleteFilename($i = 0)
    {
        $filename = $this->filename;

        // interrupt filename
        if ($i !== 0) {
            $filename .= '_' . $i;
        }

        $completeFilename = $filename . '.' . $this->extension;

        if (Storage::exists(self::UploadDirectory . $completeFilename)) {
            // file exists
            $i++;
            return $this->getCompleteFilename($i);
        }

        return $completeFilename;
    }

    /**
     * Return the filename which is passed or get from file
     * Filename is slug
     *
     * @param $filename
     * @return string
     */
    private function getFilename($filename)
    {
        if (empty($filename)) {
            // use filename from uploaded file
            $filename = str_replace('.' . $this->extension, '', $this->image->getClientOriginalName());
        }

        $slugify = new Slugify();
        return $slugify->slugify($filename);
    }
}
