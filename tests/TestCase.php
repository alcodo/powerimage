<?php

use Alcodo\PowerImage\Jobs\CreateImage;

class TestCase extends Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->originalFile = __DIR__.'/files/hochregallager.jpg';
    }

    public function tearDown()
    {
        Storage::deleteDirectory('powerimage');
        parent::tearDown();
    }

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

    public function getImage()
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile($this->originalFile, 'hochregallager.jpg');

        // convert and save
        $image = new CreateImage($file);
        $filepath = $image->handle();

        return $filepath;
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
