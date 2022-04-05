<?php


namespace Moell\Mojito\Http\Controllers;


use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    /**
     * @author moell<moell91@foxmail.com>
     * @param CaptchaBuilder $captchaBuilder
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(CaptchaBuilder $captchaBuilder)
    {
        $key = Str::uuid()->toString();

        $captcha = $captchaBuilder->build();
        $expiredAt = Carbon::now()->addMinutes(config("mojito.captcha_cache_ttl", 2));

        Cache::put($key, ['code' => $captcha->getPhrase()], $expiredAt);

        return $this->success([
            'data' => [
                'key' => $key,
                'expired_at' => $expiredAt->toDateTimeString(),
                'image_content' => $captcha->inline()
            ]
        ]);
    }
}