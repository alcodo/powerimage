<?php


class ControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_allows_to_call_the_image()
    {
        $filepath = $this->getImage();

        // statuscode
        $response = $this->call('GET', $filepath);
        $this->assertEquals(200, $response->getStatusCode());

        // header
        $headers = $response->headers->all();
        $this->assertEquals($headers['content-type'][0], 'image/png');
        $this->assertEquals($headers['cache-control'][0], 'max-age=7776000, public');
        $this->assertEquals($headers['powerimage'][0], 'Compressed');
    }

    /**
     * @test
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function it_disallow_get_image()
    {
        $response = $this->call('GET', '/powerimage/check.jpg');
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_allows_to_call_the_image_with_a_prefix()
    {
        $filepath = $this->getImage('gallery/2016/08');

        // statuscode
        $response = $this->call('GET', $filepath);
        $this->assertEquals(200, $response->getStatusCode());

        // header
        $headers = $response->headers->all();
        $this->assertEquals($headers['content-type'][0], 'image/png');
        $this->assertEquals($headers['cache-control'][0], 'max-age=7776000, public');
        $this->assertEquals($headers['powerimage'][0], 'Compressed');
    }

    /**
     * @test
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function it_disallow_to_call_the_image_with_a_prefix()
    {
        $response = $this->call('GET', '/powerimage/gallery/2016/08/check.jpg');
        $this->assertEquals(404, $response->getStatusCode());
    }
}
