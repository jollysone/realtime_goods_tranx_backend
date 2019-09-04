<?php

namespace App\Http\Controllers\Api\Admin;

use function App\failure;
use function App\getInputOrDefault;
use function App\getWithPage;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminUserDetailResource;
use App\Http\Resources\AdminUserResource;
use App\Models\Credit;
use App\Models\User;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * 管理员资料
     * @param Request $request
     * @return JsonResponse
     */
    public function getCurrent(Request $request): JsonResponse
    {
        $user = $request->user;
        return success($user);
    }

    public function getMany(Request $request): JsonResponse
    {
        $phone = getInputOrDefault($request, 'phone');

        $users = User::whereNull('deleted_at');

        if ($phone) {
            $users = $users->where('phone', 'like', '%' . $phone . '%');
        }

        $total = $users->count();
        $users = getWithPage($users);

        $res = [
            'page_index' => $request->page_index,
            'page_size'  => $request->page_size,
            'total'      => $total,
            'list'       => AdminUserResource::collection($users)
        ];

        return success($res);
    }

    public function get(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'id' => [
                'required',
                'string',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
        ]);

        $user = User::where('id', $input['id'])->first();

        return success(new AdminUserDetailResource($user));
    }

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
        ]);

        $phone    = $input['phone'];
        $password = getInputOrDefault($request, 'password');
        $id       = getInputOrDefault($request, 'id', '');
        if ($id) {
            $user = User::where('id', $id)->first();
        } else {
            if (User::where('phone', $phone)->count()) {
                return failure(ErrorCode::MOBILE_EXISTED);
            }
            $user = new User();
        }

        $user->number        = $input['number'];
        $user->phone         = $phone;
        $user->department_id = $input['department_id'];
        $user->grade_id      = $input['grade_id'];
        $user->true_name     = $input['true_name'];
        $user->nick          = $input['nick'];
        $user->status        = User::STATUS_NORMAL;
        $user->role_type     = User::ROLE_TYPE_NORMAL;

        if ($password) {
            $user->password = Hash::make($password);
        }

        $user->save();

        if (empty($id)) {
            $credit          = new Credit();
            $credit->user_id = $user->id;
            $credit->save();
        }

        return success(new AdminUserResource($user));
    }
}
