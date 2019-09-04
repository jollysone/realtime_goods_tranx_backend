<?php

namespace App\Http\Controllers\Api;


use App\Helpers\CacheKey;
use App\Http\Controllers\Controller;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Mews\Captcha\Facades\Captcha;

class CaptchaController extends Controller
{
    /**
     * 获取图片验证码
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        $captcha = Captcha::create('default', true);
        unset($captcha['sensitive']);

        Cache::put(CacheKey::captcha($captcha['key']), $captcha, config('api.captcha_expire_minutes'));

        return success($captcha);
    }
}