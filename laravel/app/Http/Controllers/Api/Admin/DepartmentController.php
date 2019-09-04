<?php

namespace App\Http\Controllers\Api\Admin;

use function App\failure;
use function App\getInputOrDefault;
use function App\getWithPage;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminDepartmentResource;
use App\Http\Resources\AdminGradeResource;
use App\Http\Resources\AdminOrderResource;
use App\Http\Resources\AdminUserResource;
use App\Models\Credit;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Order;
use App\Models\User;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function getMany(Request $request): JsonResponse
    {
        $departments = Department::whereNull('deleted_at');

        $total  = $departments->count();
        $departments = getWithPage($departments);

        $res = [
            'page_index' => $request->page_index,
            'page_size'  => $request->page_size,
            'total'      => $total,
            'list'       => AdminDepartmentResource::collection($departments)
        ];

        return success($res);
    }

    public function get(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'id' => [
                'required',
                'string',
                Rule::exists('departments', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
        ]);

        $department = Department::where('id', $input['id'])->first();

        return success(new AdminDepartmentResource($department));
    }

    public function post(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'name' => 'required',
        ]);

        $id = getInputOrDefault($request, 'id', '');
        if ($id) {
            $departments = Department::where('id', $id)->first();
        } else {
            $departments = new Department();
        }

        $departments->name = $input['name'];
        $departments->save();

        return success(new AdminDepartmentResource($departments));
    }
}
