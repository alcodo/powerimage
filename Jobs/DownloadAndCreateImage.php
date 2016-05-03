<?php

namespace Alcodo\PowerImage\Jobs;

use Alcodo\PowerImage\Exceptions\DownloadFileException;
use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\DispatchesJobs;

class DownloadAndCreateImage extends Job implements SelfHandling
{
    use DispatchesJobs;

    /**
     * @var url for image
     */
    private $url;
    /**
     * @var filename
     */
    private $filename;
    /**
     * @var foldername
     */
    private $folder;
    /**
     * @var fileextension
     */
    private $fileextension;

    /**
     * Download image and create a powerimage
     * @param $url
     * @param null $filename
     * @param null $folder
     */
    public function __construct($url, $filename = null, $fileextension = null, $folder = null)
    {
        $this->url = $url;
        $this->filename = $filename;
        $this->folder = $folder;
        $this->fileextension = $fileextension;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->dispatch(
            new CreateImage(
                $this->getUploadedFile(),
                $this->filename,
                $this->folder
            )
        );
    }

    private function getUploadedFile()
    {
        // download file
        $binaryData = file_get_contents($this->url);

        if ($binaryData === false || $binaryData === null) {
            throw new DownloadFileException();
        }

        // get filename
        if(empty($this->filename) || empty($this->fileextension)){
            $file_parts = pathinfo($this->url);
            $filename = $file_parts['basename'] . '.' . $file_parts['extension'];
        }else{
            $filename = $this->filename . '.' . $this->fileextension;
        }

        // save file in cache
        var_dump(sys_get_temp_dir());
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
//        $temp_file = sys_get_temp_dir() . $filename;


        $bla = file_put_contents($temp_file, $binaryData);
//        var_dump($bla);

        $tempfile_parts = pathinfo($temp_file);

//        var_dump($tempfile_parts);
//        $tmpHandle = tmpfile();
//        $metaDatas = stream_get_meta_data($temp_file);
//        $tmpFilename = $metaDatas['uri'];
//
//
//        var_dump($tmpFilename);
//        fclose($temp_file);

        // return UploadedFile instance
//        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile($tempfile_parts['dirname'] . '/', $filename);
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile($temp_file, $filename, 'image/png', '92361');

//        dd($file);

        return $file;
    }
}
