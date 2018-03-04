<?php

namespace Tests\Unit;

use Alcodo\PowerImage\Handler\PowerImageBuilder;
use Tests\UnitTest;

class PowerImageTest extends UnitTest
{
    public function testGetOriginalFilepath()
    {
        $handler = new PowerImageBuilder();
        $this->assertEquals(
            'images/car.jpg',
            $handler->getOriginalFilepath('images/car_w=200&h=200.jpg', 'w=200&h=200')
        );
    }
}
