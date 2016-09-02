<?php


class ResizeTest extends TestCase
{
    /**
     * @test
     */
    public function it_resized_image()
    {
        $filepath = $this->getImage();
        $this->assertTrue(Storage::disk('powerimage')->exists($filepath));

        $params = [
            'w' => 200,
            'h' => 200,
        ];

        $imageOptimizer = app('Approached\LaravelImageOptimizer\ImageOptimizer');
        $ri = new \Alcodo\PowerImage\Jobs\ResizeImage($filepath, $params);
        $resizedFilepath = $ri->handle($imageOptimizer);

        $this->assertEquals('/powerimage/w_200,h_200/example.png', $resizedFilepath);

        $this->assertTrue(Storage::disk('powerimage')->exists($resizedFilepath));
        $sizeOriginal = Storage::disk('powerimage')->size($filepath);
        $sizeResized = Storage::disk('powerimage')->size($resizedFilepath);
        $this->assertLessThan($sizeOriginal, $sizeResized);
    }
}
