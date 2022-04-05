<?php

namespace Moell\Mojito\Tests\Feature;


class CaptchaControllerTest extends FeatureTestCase
{
    public function test_generate()
    {
        $response = $this->get('/api/captcha', $this->jsonHeader());

        $response->assertJson([
            'data' => [
                'key' => true,
                'expired_at' => true,
                'image_content' => true
            ]
        ]);
    }
}
