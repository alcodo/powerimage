<?php

namespace Tests\Unit;

use Alcodo\PowerImage\Handler\ParamsHelper;
use Alcodo\PowerImage\Handler\PowerImageBuilder;
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

}
