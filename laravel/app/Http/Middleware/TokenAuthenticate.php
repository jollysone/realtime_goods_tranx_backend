<?php

namespace App\Http\Middleware;

use function App\failure;
use App\Helpers\CacheKey;
use App\Helpers\ErrorCode;
use App\Models\Token;
use App\Models\User;
use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class TokenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param int                       $roleType
     * @return mixed
     */
    public function handle($request, Closure $next, $roleType = User::ROLE_TYPE_NORMAL)
    {
        // 获取 Token
        $token = $request->header('Auth-Token', '');
        if (!$token) {
            return failure(ErrorCode::TOKEN_MISSING);
        }

        // 查找 Token 对应的 Auth ID (先从缓存读取, 缓存不存在则从数据库读取)
        $tokenModel = Cache::get(CacheKey::token($token), function () use ($token) {
            // 从数据库查找 Token
            $t = Token::where('token', $token)
                      ->where('expire_at', '>', Carbon::now())
                      ->first();

            // Token 不存在
            if (!$t) {
                return null;
            }

            // 缓存 Auth ID
            Cache::put(CacheKey::token($token), $t, Carbon::parse($t->expire_at));

            return $t;
        });

        if (!$tokenModel) {
            return failure(ErrorCode::TOKEN_INVALID);
        }

        $user = User::where('id', $tokenModel->user_id)->first();

        if (!$user || $user->status != User::STATUS_NORMAL) {
            return failure(ErrorCode::ACCOUNT_DISABLED);
        }

        if ($roleType != User::ROLE_TYPE_NORMAL && $user->role_type != $roleType) {
            return failure(ErrorCode::PERMISSION_DENIED);
        }

        $request->user  = $user;
        $request->token = $token;

        return $next($request);
    }
}
