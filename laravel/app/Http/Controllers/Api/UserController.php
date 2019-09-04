<?php

namespace App\Http\Controllers\Api;

use function App\checkCaptcha;
use function App\failure;
use function App\getInputOrDefault;
use App\Helpers\CacheKey;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileResource;
use App\Models\Credit;
use App\Models\Token;
use App\Models\User;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * 用户注册
     * @param Request $request
     * @return JsonResponse
     */
    public function post(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'number'        => 'required',
            'phone'         => 'required',
            'grade_id'      => [
                'required',
                Rule::exists('grades', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
            'department_id' => [
                'required',
                Rule::exists('departments', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
            'true_name'     => 'required',
            'nick'          => 'required',
            'password'      => 'required',
            'captcha'       => 'required',
            'captcha_key'   => 'required',
        ]);

        $phone      = $input['phone'];
        $captcha    = $input['captcha'];
        $captchaKey = $input['captcha_key'];

        if (!checkCaptcha($captchaKey, $captcha)) {
            return failure(ErrorCode::CAPTCHA_ERROR);
        }

        if (User::where('phone', $phone)->count()) {
            return failure(ErrorCode::MOBILE_EXISTED);
        }

        $user                = new User();
        $user->number        = $input['number'];
        $user->phone         = $phone;
        $user->department_id = $input['department_id'];
        $user->grade_id      = $input['grade_id'];
        $user->true_name     = $input['true_name'];
        $user->nick          = $input['nick'];
        $user->password      = Hash::make(getInputOrDefault($request, 'password'));
        $user->status        = User::STATUS_NORMAL;
        $user->role_type     = User::ROLE_TYPE_NORMAL;
        $user->save();

        $credit          = new Credit();
        $credit->user_id = $user->id;
        $credit->save();

        return success();
    }

    /**
     * 账号资料
     * @param Request $request
     * @return JsonResponse
     */
    public function getCurrent(Request $request): JsonResponse
    {
        return success(new UserProfileResource($request->user));
    }

    public function putProfile(Request $request): JsonResponse
    {
        $user  = $request->user;
        $input = $this->validate($request, [
            'number'    => 'required',
            'true_name' => 'required',
            'phone'     => 'required',
            'nick'      => 'required',
        ]);

        $number = $input['number'];
        $phone  = $input['phone'];

        $userExists = User::where('number', $number)->where('id', '<>', $user->id)->first();
        if ($userExists) {
            return failure(ErrorCode::USER_NUMBER_EXISTED);
        }

        $userExists = User::where('phone', $phone)->where('id', '<>', $user->id)->first();
        if ($userExists) {
            return failure(ErrorCode::MOBILE_EXISTED);
        }

        $user->number    = $number;
        $user->true_name = $input['true_name'];
        $user->phone     = $phone;
        $user->nick      = $input['nick'];

        $password = getInputOrDefault($request, 'password', '');
        if ($password) {
            $user->password = Hash::make($password);
        }

        $user->save();

        if ($password) {
            Token::remove($request->token);
        }
        return success(new UserProfileResource($request->user));
    }

    public function putPasswordByRetrieve(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'number'      => 'required',
            'phone'       => 'required',
            'true_name'   => 'required',
            'password'    => 'required',
            'captcha'     => 'required',
            'captcha_key' => 'required',
        ]);

        $captcha    = $input['captcha'];
        $captchaKey = $input['captcha_key'];

        if (!checkCaptcha($captchaKey, $captcha)) {
            return failure(ErrorCode::CAPTCHA_ERROR);
        }

        $number   = $input['number'];
        $phone    = $input['phone'];
        $trueName = $input['true_name'];

        $user = User::where('number', $number)->where('phone', $phone)->where('true_name', $trueName)->first();
        if (empty($user)) {
            return failure(ErrorCode::USER_NOT_EXIST, '用户不存在');
        }

        $user->password = Hash::make($input['password']);
        $user->save();

        $oldTokens = Token::where('user_id', $user->id)->get();

        foreach ($oldTokens as $oldToken) {
            Cache::forget(CacheKey::token($oldToken->token));
            $oldToken->delete();
        }

        return success();
    }
}