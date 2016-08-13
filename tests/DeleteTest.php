<?php

use Alcodo\PowerImage\Jobs\DeleteImage;
use Illuminate\Support\Facades\Storage;

class DeleteTest extends TestCase
{
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
            []
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
            []
        );

        $this->assertEquals('/powerimage/.cache/example/hochregallager.jpg/', $imageCachePath);
    }

    /**
     * @test
     */
    public function it_removes_cache_files()
    {
        // generate resize image
        $filepath = $this->getImage();
<<<<<<< HEAD
        $url = $filepath . '?w=200';
        try{
            // Cannot modify header information - headers already sent by
            $this->call('GET', $url);
        }catch (\Exception $e){
        }
=======
        $url = $filepath.'?w=200';
        $this->call('GET', $url);
>>>>>>> 6e61fcba4f6a78d360c102f6795975c838d13f31

        $files = Storage::allFiles('powerimage');
        $this->assertCount(2, $files);

        // delete
        $deleter = new DeleteImage($filepath);
        $result = $deleter->handle();
        $this->assertTrue($result);

        $files = Storage::allFiles('powerimage');
        $this->assertCount(0, $files);
    }
}
