<?php

use Alcodo\PowerImage\Jobs\CreateImage;
use Illuminate\Support\Facades\Storage;

class CreateTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $exampleFile = __DIR__ . '/files/example.png';
        $temp_file = sys_get_temp_dir() . '/example.png';
        copy($exampleFile, $temp_file);

        $this->tempFile = $temp_file;
    }

    public function tearDown()
    {
        Storage::deleteDirectory('powerimage');
        unlink($this->tempFile);
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_allows_create_a_image_without_gallery()
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile($this->tempFile, 'hochregallager.png');
        $fileSizeBefore = filesize($this->tempFile);

        // convert and save
        $image = new CreateImage($file);
        $imageOptimizer = app('Approached\LaravelImageOptimizer\ImageOptimizer');
        $filepath = $image->handle($imageOptimizer);

        $this->assertEquals('/powerimage/hochregallager.png', $filepath);

        // file exists
        $this->assertTrue(Storage::exists($filepath));

        // file is optimized
        $this->assertLessThan($fileSizeBefore, Storage::size($filepath), 'Image optimization doesn\'t works');
    }

    /**
     * @test
     */
    public function it_allows_create_a_image_with_gallery()
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile($this->tempFile, 'regal.png');
        $fileSizeBefore = filesize($this->tempFile);

        // convert and save
        $image = new CreateImage($file, null, 'galleryFolder');
        $imageOptimizer = app('Approached\LaravelImageOptimizer\ImageOptimizer');
        $filepath = $image->handle($imageOptimizer);

        $this->assertEquals('/powerimage/galleryFolder/regal.png', $filepath);

        // file exists
        $this->assertTrue(Storage::exists($filepath));

        // file is optimized
        $this->assertLessThan($fileSizeBefore, Storage::size($filepath), 'Image optimization doesn\'t works');
    }
}
