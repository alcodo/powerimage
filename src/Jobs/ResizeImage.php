<?php

namespace Alcodo\PowerImage\Jobs;

use Approached\LaravelImageOptimizer\ImageOptimizer;
use Cocur\Slugify\Slugify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ResizeImage implements SelfHandling
{
    use Queueable;

    /**
     * @var string
     */
    private $orignalFile;
    /**
     * @var array
     */
    private $params;

    /**
     * Create a new job instance.
     *
     * @param UploadedFile $image
     * @param null $filename (Without fileextension)
     * @param null $folder (Format: 'example/')
     */

    /**
     * ResizeImage constructor.
     * @param $image
     * @param $params
     */

    /**
     * ResizeImage constructor.
     * @param string $orignalFile
     * @param array $params
     */
    public function __construct($orignalFile, $params)
    {
        $this->orignalFile = $orignalFile;
        $this->params = $params;
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
        $imageOptimizer->optimizeUploadedImageFile($this->image);

        // save
        Storage::put($filepath, File::get($this->image));

        return $filepath;
    }
//
//    /**
//     * Generates a filename and check that file is not exists.
//     *
//     * @param int $i
//     * @return string
//     */
//    protected function getCompleteFilename($i = 0)
//    {
//        $filename = $this->filename;
//
//        // interrupt filename
//        if ($i !== 0) {
//            $filename .= '_' . $i;
//        }
//
//        $completeFilename = $filename . '.' . $this->extension;
//
//        // file exists
//        if (Storage::exists($this->getFolder() . $completeFilename)) {
//            $i++;
//
//            return $this->getCompleteFilename($i);
//        }
//
//        return $completeFilename;
//    }
//
//    /**
//     * Return the filename which is passed or get from file
//     * Filename is slug.
//     *
//     * @param $filename
//     * @return string
//     */
//    protected function getFilename($filename)
//    {
//        if (empty($filename)) {
//            // use filename from uploaded file
//            $filename = str_replace('.' . $this->extension, '', $this->image->getClientOriginalName());
//        }
//
//        $slugify = new Slugify();
//
//        return $slugify->slugify($filename);
//    }
//
//    protected function getFilepath()
//    {
//        return $this->getFolder() . $this->getCompleteFilename();
//    }
//
//    protected function getFolder()
//    {
//        if (is_null($this->folder) || empty($this->folder)) {
//            return '/' . self::UploadDirectory . '/';
//        } else {
//            // remove front and last slash
//            $this->folder = ltrim($this->folder, '/');
//            $this->folder = rtrim($this->folder, '/');
//
//            return '/' . self::UploadDirectory . '/' . $this->folder . '/';
//        }
//    }
}
