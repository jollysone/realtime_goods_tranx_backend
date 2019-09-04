<?php

namespace App\Models;

use function App\getClientIP;
use App\Helpers\CacheKey;
use function App\randomStr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Token extends Model
{
    use SoftDeletes;

    const APP_TYPE_ANDROID        = 1;
    const APP_TYPE_ADMIN          = 2;
    const APP_TYPE_TESTING        = 3;

    protected $hidden   = ['deleted_at'];
    protected $fillable = ['app_type', 'user_id', 'token', 'expire_at', 'ip'];

    /**
     * 获得所有 AppType
     * @return array
     */
    static public function getAllAppTypes(): array
    {
        return [
            self::APP_TYPE_ANDROID, self::APP_TYPE_ADMIN, self::APP_TYPE_TESTING
        ];
    }

    /**
     * 生成新 Token
     * @param int    $appType
     * @param string $userId
     * @return Token
     */
    static public function generate(int $appType, string $userId): Token
    {
        $oldTokens = self::where('app_type', $appType)->where('user_id', $userId)->get();
//        $oldTokens = self::where('user_id', $userId)->get();

        foreach ($oldTokens as $oldToken) {
            Cache::forget(CacheKey::token($oldToken->token));
            $oldToken->delete();
        }

        $token = self::create([
            'app_type'  => $appType,
            'user_id'   => $userId,
            'ip'        => getClientIP(),
            'token'     => randomStr(config('api.token_length')),
            'expire_at' => now()->addMinutes(config('api.token_expire_minutes'))
        ]);

        Cache::put(CacheKey::token($token->token), $token, Carbon::parse($token->expire_at));

        return $token;
    }

    /**
     * 删除 Token，清理缓存
     * @param $token
     */
    static public function remove($token)
    {
        $tokenModel = self::where('token', $token)->first();

        if ($tokenModel) {
            Cache::forget(CacheKey::token($tokenModel->token));
            $tokenModel->delete();
        }
    }
}
