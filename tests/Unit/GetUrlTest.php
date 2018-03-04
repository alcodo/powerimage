<?php

namespace Tests\Unit;

use Alcodo\PowerImage\Handler\PowerImageBuilder;
use Tests\UnitTest;

class GetUrlTest extends UnitTest
{
    public function testGet()
    {
        $builder = new PowerImageBuilder();
        $this->assertEquals(
            'images/video_w=200&h=350.png',
            $builder->path('images/video.png', ['w' => 200, 'h' => 350])
        );
    }
}
