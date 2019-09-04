<?php

namespace App\Http\Controllers\Api\Admin;

use function App\failure;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\User;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    /**
     * 登录 by 密码
     * @param Request $request
     * @return JsonResponse
     */
    public function createByPwd(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $input['phone'])->first();
        if (!$user) {
            return failure(ErrorCode::ACCOUNT_OR_PWD_INVALID);
        }

        if (!Hash::check($input['password'], $user->password)) {
            return failure(ErrorCode::ACCOUNT_OR_PWD_INVALID);
        }

        if ($user->status != User::STATUS_NORMAL) {
            return failure(ErrorCode::ACCOUNT_DISABLED);
        }

        if ($user->role_type != User::ROLE_TYPE_ADMIN) {
            return failure(ErrorCode::PERMISSION_DENIED, '非管理员账号');
        }

        $token = $user->getLoginToken(Token::APP_TYPE_ADMIN);

        return success([
            'token' => $token->token
        ]);
    }

    /**
     * 退出登录
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        Token::remove($request->token);
        return success();
    }
}
