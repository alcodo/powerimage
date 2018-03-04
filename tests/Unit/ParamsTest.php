<?php

namespace Tests\Unit;

use Alcodo\PowerImage\Handler\ParamsHelper;
use Tests\UnitTest;

class ParamsTest extends UnitTest
{
    public function testParse()
    {
        $this->assertEquals(
            [
                'w' => '200',
                'h' => '200',
            ],
            ParamsHelper::parseToArray('w=200&h=200')
        );
    }

    public function testGetParameterString()
    {
        $this->assertEquals(
            'w=200&h=200',
            ParamsHelper::getParameterString('images/car_w=200&h=200.jpg', 'jpg'));

        $this->assertFalse(ParamsHelper::getParameterString('images/cow.jpg', 'jpg'));
        $this->assertFalse(ParamsHelper::getParameterString('images/girl_.jpg', 'jpg'));
    }
}
