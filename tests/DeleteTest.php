<?php

use Illuminate\Support\Facades\Storage;

class DeleteTest extends TestCase
{
    /**
     * @test
     */
    public function it_allows_delete_the_image_and_resized_image()
    {
        // create
        $this->getImage();

        $params = [
            'w' => 200,
            'h' => 200,
        ];

        $imageOptimizer = app('Approached\LaravelImageOptimizer\ImageOptimizer');
        $ri = new \Alcodo\PowerImage\Jobs\ResizeImage('example.png', [
            'w' => 200,
            'h' => 200,
        ]);
        $ri->handle($imageOptimizer);
        $ri = new \Alcodo\PowerImage\Jobs\ResizeImage('example.png', [
            'w' => 300,
            'h' => 300,
        ]);
        $ri->handle($imageOptimizer);

        // files exists
        $this->assertTrue(Storage::disk('powerimage')->exists('example.png'));
        $this->assertTrue(Storage::disk('powerimage')->exists('/w_200,h_200/example.png'));
        $this->assertTrue(Storage::disk('powerimage')->exists('/w_300,h_300/example.png'));

        // delete
        $deleteJob = new \Alcodo\PowerImage\Jobs\DeleteImage('example.png', $params);
        $result = $deleteJob->handle();

        // testing
        $this->assertTrue($result);
        $this->assertFalse(Storage::disk('powerimage')->exists('example.png'));
        $this->assertFalse(Storage::disk('powerimage')->exists('/w_200,h_200/example.png'));
        $this->assertFalse(Storage::disk('powerimage')->exists('/w_300,h_300/example.png'));
    }
}
