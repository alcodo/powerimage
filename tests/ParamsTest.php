<?php

use Alcodo\PowerImage\Utilities\ParamsHelper;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ParamsTest extends TestCase
{

    /**
     * @test
     */
    public function it_get_rights_params()
    {
        $prefix = 'powerimage/gallery/foo/w_200,h_200';
        $params = ParamsHelper::getParamsFromPrefix($prefix);

        $expectedResults = array(
            'w' => 200,
            'h' => 200,
        );

        $this->assertEquals($expectedResults, $params);
    }

    /**
     * @test
     */
    public function it_get_rights_params_2()
    {
        $prefix = 'powerimage/gallery/foo/h_200';
        $params = ParamsHelper::getParamsFromPrefix($prefix);

        $expectedResults = array(
            'h' => 200,
        );

        $this->assertEquals($expectedResults, $params);
    }

    /**
     * @test
     */
    public function it_get_rights_params_3()
    {
        $prefix = 'h_200';
        $params = ParamsHelper::getParamsFromPrefix($prefix);

        $expectedResults = array(
            'h' => 200,
        );

        $this->assertEquals($expectedResults, $params);
    }

}
