<?php

use Alcodo\PowerImage\Utilities\ParamsHelper;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResizeTest extends TestCase
{

    /**
     * @test
     */
    public function it_resized_image()
    {
        $filepath = $this->getImage();
        $params = array(
            'w' => 200,
            'h' => 200,
        );

        $ri = new \Alcodo\PowerImage\Jobs\ResizeImage($filepath, $params);
        $resizedFilepath = $ri->handle();

        $this->assertEquals('/powerimage/w_200,h_200/example.png', $resizedFilepath);

    }

}
