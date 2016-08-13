<?php

class TestCase extends Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['Alcodo\PowerImage\PowerImageServiceProvider'];
    }

    public function callPrivateOrProtectedMethod($obj, $name, array $args)
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }

    /**
     * @test
     */
    public function it_allows_to_use_service()
    {
        $this->assertNotEmpty(
            app('Approached\LaravelImageOptimizer\ImageOptimizer')
        );
    }

}
