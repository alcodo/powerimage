<?php


class ResizeTest extends TestCase
{
    /**
     * @test
     */
    public function it_resized_image()
    {
        $filepath = $this->getImage();
        $params = [
            'w' => 200,
            'h' => 200,
        ];

        $ri = new \Alcodo\PowerImage\Jobs\ResizeImage($filepath, $params);
        $resizedFilepath = $ri->handle();

        $this->assertEquals('/powerimage/w_200,h_200/example.png', $resizedFilepath);
    }
}
