<?php

namespace Moell\Mojito\Tests\Unit;


use Moell\Mojito\Http\MojitoResponse;
use Moell\Mojito\Tests\TestCase;

class ResponseTest extends TestCase
{
    use MojitoResponse;

    protected function setUp()
    {
        $this->setUpTheTestEnvironment();
    }

    public function test_http_ok()
    {
        $data = ['name' => 'moell'];

        $response = $this->success($data);

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertJsonStringEqualsJsonString(json_encode($data), $response->getContent());
    }

    public function test_http_created()
    {
        $response = $this->created();

        $this->assertEquals($response->getStatusCode(), 201);
    }

    public function test_http_accepted()
    {
        $response = $this->accepted();

        $this->assertEquals($response->getStatusCode(), 202);
    }

    public function test_http_not_content()
    {
        $response = $this->noContent();

        $this->assertEquals($response->getStatusCode(), 204);
    }

    public function test_http_bad_request()
    {
        $response = $this->badRequest('error');

        $this->assertEquals($response->getStatusCode(), 400);
        $this->assertJsonStringEqualsJsonString(json_encode(['message' => 'error']), $response->getContent());
    }

    public function test_http_unauthorized()
    {
        $response = $this->unauthorized('unauthorized');

        $this->assertEquals($response->getStatusCode(), 401);
        $this->assertJsonStringEqualsJsonString(json_encode(['message' => 'unauthorized']), $response->getContent());
    }

    public function test_http_forbidden()
    {
        $response = $this->forbidden('forbidden');

        $this->assertEquals($response->getStatusCode(), 403);
        $this->assertJsonStringEqualsJsonString(json_encode(['message' => 'forbidden']), $response->getContent());
    }

    public function test_http_unprocesable_etity()
    {
        $response = $this->unprocesableEtity();

        $this->assertEquals($response->getStatusCode(), 422);
    }
}