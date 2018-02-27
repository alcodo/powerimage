<?php

namespace Tests\Unit;

use Alcodo\PowerImage\Handler\PowerImageBuilder;
use Tests\UnitTest;

class PowerImageTest extends UnitTest
{

    public function testGetParameterString()
    {
        $handler = new PowerImageBuilder();
        $this->assertEquals(
            'w=200&h=200',
            $handler->getParameterString('images/car_w=200&h=200.jpg', 'jpg'));

        $this->assertFalse($handler->getParameterString('images/cow.jpg', 'jpg'));
        $this->assertFalse($handler->getParameterString('images/girl_.jpg', 'jpg'));
    }

    public function testGetOriginalFilepath()
    {
        $handler = new PowerImageBuilder();
        $this->assertEquals(
            'images/car.jpg',
            $handler->getOriginalFilepath('images/car_w=200&h=200.jpg', 'w=200&h=200')
        );
    }

}
