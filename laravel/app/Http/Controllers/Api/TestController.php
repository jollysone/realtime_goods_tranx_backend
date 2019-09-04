<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use function App\success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    /**
     * 测试用
     * @param Request $request
     * @return mixed
     */
    public function test(Request $request)
    {
        var_dump(Hash::make('111111'));
        return success();
    }

    public function flushCache(Request $request)
    {
        Cache::flush();
        return success();
    }
}