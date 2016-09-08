<?php

namespace Alcodo\PowerImage\Jobs;

use Alcodo\PowerImage\Exceptions\DownloadFileException;

class DownloadAndCreateImage
{

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
     * Download image and create a powerimage.
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
        $image = new CreateImage(
            $this->getUploadedFile(),
            $this->filename,
            $this->folder
        );
        return $image->handle();
    }

    protected function getUploadedFile()
    {
        $binaryData = $this->downloadFile();
        $filename = $this->getFilenameWithExtension();

        // create cache file
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        file_put_contents($temp_file, $binaryData);

        // return UploadedFile instance
        return new \Symfony\Component\HttpFoundation\File\UploadedFile($temp_file, $filename);
    }

    /**
     * Download a file and return binary data.
     *
     * @return mixed
     * @throws DownloadFileException
     */
    private function downloadFile()
    {
        // download file
        $binaryData = file_get_contents($this->url);

        if ($binaryData === false || $binaryData === null) {
            throw new DownloadFileException();
        }

        return $binaryData;
    }

    /**
     * Filename with extension
     * Example: dog.png.
     *
     * @return string
     */
    private function getFilenameWithExtension()
    {
        if (empty($this->filename) || empty($this->fileextension)) {

            // use filename from url
            $file_parts = pathinfo($this->url);

            return $file_parts['basename'] . '.' . $file_parts['extension'];
        } else {
            return $this->filename . '.' . $this->fileextension;
        }
    }
}
