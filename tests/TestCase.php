<?php

use Alcodo\PowerImage\Jobs\CreateImage;
use Illuminate\Foundation\Bus\DispatchesJobs;

class TestCase extends Orchestra\Testbench\TestCase
{
    use DispatchesJobs;

    public function setUp()
    {
        parent::setUp();

        $exampleFile = __DIR__.'/files/example.png';

        $temp_file = sys_get_temp_dir().'/example.png';
        copy($exampleFile, $temp_file);

        $this->tempFile = $temp_file;
    }

    public function tearDown()
    {
        $directories = Storage::disk('powerimage')->directories();
        foreach ($directories as $dir) {
            Storage::disk('powerimage')->deleteDirectory($dir);
        }
        $files = Storage::disk('powerimage')->allFiles();
        Storage::disk('powerimage')->delete($files);

        unlink($this->tempFile);
        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            \Alcodo\PowerImage\PowerImageServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $fileSystemSettings = [
            'driver' => 'local',
            'root' => storage_path('powerimage'),
        ];

        $app['config']->set('filesystems.disks.powerimage', $fileSystemSettings);
        $app['config']->set('app.key', '9lLnUPN2eZbZCmtisUatFc6x8t64kL0s');
    }

    public function callPrivateOrProtectedMethod($obj, $name, array $args)
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $args);
    }

    public function getImage($folder = null)
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile($this->tempFile, 'example.png');

        // convert and save
        $image = new CreateImage($file, null, $folder);
        $filepath = $this->dispatch($image);

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
