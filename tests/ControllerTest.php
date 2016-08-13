<?php

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ControllerTest extends TestCase
{
    public function tearDown()
    {
        Storage::deleteDirectory('powerimage');
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_allows_to_call_the_image()
    {
        $filepath = $this->getImage();

        $this->visit($filepath)->assertResponseOk();

        try {
            // Cannot modify header information - headers already sent by
            $this->assertEquals(
                404,
                $this->call('GET', 'powerimage/hoch.jpg')->getStatusCode()
            );
        } catch (NotFoundHttpException $e) {
        }
    }

    /**
     * @test
     */
    public function it_allows_create_resized_image()
    {
        $filepath = $this->getImage();
        $url = $filepath.'?w=200';

        try {
            // Cannot modify header information - headers already sent by
            $this->assertEquals(
                500,
                $this->call('GET', $url)->getStatusCode()
            );
        } catch (\Exception $e) {
        }

        $files = Storage::allFiles('powerimage');

        $this->assertCount(2, $files);
        $this->assertEquals(
            'powerimage/.cache/hochregallager.jpg/d85148add38210b585ffdd38c7128d85',
            $files[0]
        );
    }
}
