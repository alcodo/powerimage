<?php

namespace Tests\Unit;

use Alcodo\PowerImage\Handler\PowerImageBuilder;
use Illuminate\Http\Request;
use Tests\UnitTest;

class ExcludeIncludeTest extends UnitTest
{
    /**
     * @var \Illuminate\Http\Request $request
     */
    protected $request;

    /**
     * @var \Alcodo\PowerImage\Handler\PowerImageBuilder $builder
     */
    protected $builder;

    protected function setUp()
    {
        $this->request = new Request();
        $this->builder = new PowerImageBuilder();
    }

    public function testInclude()
    {
        $this->request->server->set('REQUEST_URI', '/images/girl.jpg');

        // allow
        $this->assertTrue($this->builder->include(
            $this->request, ['/images/*']
        ));
        $this->assertTrue($this->builder->include(
            $this->request, ['/images*']
        ));
        $this->assertTrue($this->builder->include(
            $this->request, ['/images/girl*']
        ));

        // not allow
        $this->assertFalse($this->builder->include(
            $this->request, ['/gallery/*']
        ));
        $this->assertFalse($this->builder->include(
            $this->request, ['/gallery*']
        ));
        $this->assertFalse($this->builder->include(
            $this->request, ['/gallery/girl*']
        ));
        $this->assertFalse($this->builder->include(
            $this->request, ['images*']
        ));
    }

    public function testExclude()
    {
        $this->request->server->set('REQUEST_URI', '/images/car.jpg');

        // not allow (no powerimage access)
        $this->assertFalse($this->builder->exclude(
            $this->request, ['/images/*']
        ));

        // allow
        $this->assertTrue($this->builder->exclude(
            $this->request, ['/gallery/*']
        ));
    }
}
