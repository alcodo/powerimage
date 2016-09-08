<?php

namespace Alcodo\PowerImage\Jobs;

use Alcodo\PowerImage\Utilities\UrlHelper;
use Approached\LaravelImageOptimizer\ImageOptimizer;
use Cocur\Slugify\Slugify;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateImage implements SelfHandling
{
    protected $realPath;
    protected $filename;
    protected $extension;
    protected $folder;
    protected $path;

    /**
     * Create a new job instance.
     *
     * @param UploadedFile $image
     * @param null $filename (Without fileextension)
     * @param null $folder (Format: 'example/')
     */
    public function __construct(UploadedFile $image, $filename = null, $folder = null)
    {
        $this->realPath = $image->getRealPath();
        $this->extension = $image->getClientOriginalExtension();
        $this->filename = $this->getFilename($image, $filename);
        $this->folder = $folder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImageOptimizer $imageOptimizer)
    {
        $filepath = $this->getFilepath();

        // optimize (overwrite image file)
        $imageOptimizer->optimizeImage($this->realPath);

        // save
        Storage::disk('powerimage')->put($filepath, File::get($this->realPath));

        return UrlHelper::getPowerImageUrlPath($filepath);
    }

    /**
     * Generates a filename and check that file is not exists.
     *
     * @param int $i
     * @return string
     */
    protected function getCompleteFilename($i = 0)
    {
        $filename = $this->filename;

        // interrupt filename
        if ($i !== 0) {
            $filename .= '_'.$i;
        }

        $completeFilename = $filename.'.'.$this->extension;

        // file exists
        if (Storage::exists($this->getFolder().$completeFilename)) {
            $i++;

            return $this->getCompleteFilename($i);
        }

        return $completeFilename;
    }

    /**
     * Return the filename which is passed or get from file
     * Filename is slug.
     *
     * @param UploadedFile $image
     * @param $filename
     * @return string
     */
    protected function getFilename(UploadedFile $image, $filename)
    {
        if (empty($filename)) {
            // use filename from uploaded file
            $filename = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
        }

        $slugify = new Slugify();

        return $slugify->slugify($filename);
    }

    protected function getFilepath()
    {
        return $this->getFolder().$this->getCompleteFilename();
    }

    protected function getFolder()
    {
        if (is_null($this->folder) || empty($this->folder)) {
            return '/';
        } else {
            // remove front and last slash
            $this->folder = ltrim($this->folder, '/');
            $this->folder = rtrim($this->folder, '/');

            return '/'.$this->folder.'/';
        }
    }

}
