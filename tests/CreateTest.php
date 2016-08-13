<?php

use Alcodo\PowerImage\Jobs\CreateImage;
use Illuminate\Support\Facades\Storage;

class CreateTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->originalFile = __DIR__.'/files/hochregallager.jpg';
    }

    public function tearDown()
    {
        Storage::deleteDirectory('powerimage');
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_allows_create_a_image_without_gallery()
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile($this->originalFile, 'hochregallager.jpg');

        // convert and save
        $image = new CreateImage($file);
        $filepath = $image->handle();

        $this->assertEquals('/powerimage/hochregallager.jpg', $filepath);

        // file exists
        $this->assertTrue(Storage::exists($filepath));
    }

    /**
     * @test
     */
    public function it_allows_create_a_image_with_gallery()
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile($this->originalFile, 'regal.jpg');

        // convert and save
        $image = new CreateImage($file, null, 'galleryFolder');
        $filepath = $image->handle();

        $this->assertEquals('/powerimage/galleryFolder/regal.jpg', $filepath);

        // file exists
        $this->assertTrue(Storage::exists($filepath));
    }
}
