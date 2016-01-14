<?php

namespace Alcodo\PowerImage\Jobs;

use App\Jobs\Job;
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

        // filename
        $this->extension = $this->image->getClientOriginalExtension();

        if (is_null($filename)) {
            $filename = $this->image->getClientOriginalName();
        }

        // TODO slugify
        // https://github.com/cocur/slugify
        $this->filename = $filename;

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
}
