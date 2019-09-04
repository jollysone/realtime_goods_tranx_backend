<?php

namespace App\Http\Controllers\Api;

use function App\failure;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\User;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
            'app_type' => ['required', Rule::in(Token::getAllAppTypes())]
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

        $token = $user->getLoginToken($input['app_type']);

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