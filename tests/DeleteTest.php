<?php

use Alcodo\PowerImage\Jobs\CreateImage;
use Alcodo\PowerImage\Jobs\DeleteImage;
use Illuminate\Support\Facades\Storage;

class DeleteTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->originalFile = __DIR__ . '/files/hochregallager.jpg';
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
        $filepath = $this->getImage();
        $this->assertTrue(Storage::exists($filepath));

        $deleter = new DeleteImage($filepath);
        $result = $deleter->handle();
        $this->assertTrue($result);

        // check image exists
        $this->assertFalse(Storage::exists($filepath));
    }

    /**
     * @test
     */
    public function it_get_correct_cache_path()
    {
        $deleter = new DeleteImage('/powerimage/hochregallager.jpg');

        $imageCachePath = $this->callPrivateOrProtectedMethod(
            $deleter,
            'getImageCachePath',
            array()
        );

        $this->assertEquals('/powerimage/.cache/hochregallager.jpg/', $imageCachePath);
    }

    /**
     * @test
     */
    public function it_get_correct_cache_path_with_folder()
    {
        $deleter = new DeleteImage('/powerimage/example/hochregallager.jpg');

        $imageCachePath = $this->callPrivateOrProtectedMethod(
            $deleter,
            'getImageCachePath',
            array()
        );

        $this->assertEquals('/powerimage/.cache/example/hochregallager.jpg/', $imageCachePath);
    }


    public function getImage()
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile($this->originalFile, 'hochregallager.jpg');

        // convert and save
        $image = new CreateImage($file);
        $filepath = $image->handle();
        return $filepath;
    }
}
