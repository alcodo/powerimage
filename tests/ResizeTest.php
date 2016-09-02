<?php


class ResizeTest extends TestCase
{
    /**
     * @test
     */
    public function it_resized_image()
    {
        $filepath = $this->getImage();
        $this->assertTrue(Storage::disk('powerimage')->exists('example.png'));

        $params = [
            'w' => 200,
            'h' => 200,
        ];

        $imageOptimizer = app('Approached\LaravelImageOptimizer\ImageOptimizer');
        $ri = new \Alcodo\PowerImage\Jobs\ResizeImage('example.png', $params);
        $resizedFilepath = $ri->handle($imageOptimizer);

        $this->assertEquals('/powerimage/w_200,h_200/example.png', $resizedFilepath);

        $this->assertTrue(Storage::disk('powerimage')->exists('/w_200,h_200/example.png'));
        $sizeOriginal = Storage::disk('powerimage')->size('example.png');
        $sizeResized = Storage::disk('powerimage')->size('/w_200,h_200/example.png');
        $this->assertLessThan($sizeOriginal, $sizeResized);
    }
}
