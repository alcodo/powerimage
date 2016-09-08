<?php

use Alcodo\PowerImage\Jobs\CreateImage;
use Illuminate\Support\Facades\Storage;

class CreateTest extends TestCase
{
    /**
     * @test
     */
    public function it_allows_create_a_image_without_gallery()
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile($this->tempFile, 'hochregallager.png');
        $fileSizeBefore = filesize($this->tempFile);

        // convert and save
        $image = new CreateImage($file);
        $filepath = $this->dispatch($image);

        $this->assertEquals('/powerimage/hochregallager.png', $filepath);

        // file exists
        $this->assertTrue(Storage::disk('powerimage')->exists('hochregallager.png'));

        // file is optimized
        $this->assertLessThan($fileSizeBefore, Storage::disk('powerimage')->size('hochregallager.png'), 'Image optimization doesn\'t works');
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
        $filepath = $this->dispatch($image);

        $this->assertEquals('/powerimage/galleryFolder/regal.png', $filepath);

        // file exists
        $this->assertTrue(Storage::disk('powerimage')->exists('/galleryFolder/regal.png'));

        // file is optimized
        $this->assertLessThan($fileSizeBefore, Storage::disk('powerimage')->size('/galleryFolder/regal.png'), 'Image optimization doesn\'t works');
    }
}
