<?php

namespace App\Http\Controllers\Api\Admin;

use function App\failure;
use function App\getInputOrDefault;
use function App\getWithPage;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminGradeResource;
use App\Http\Resources\AdminOrderResource;
use App\Http\Resources\AdminUserResource;
use App\Models\Credit;
use App\Models\Grade;
use App\Models\Order;
use App\Models\User;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    public function getMany(Request $request): JsonResponse
    {
        $grades = Grade::whereNull('deleted_at');

        $total  = $grades->count();
        $grades = getWithPage($grades);

        $res = [
            'page_index' => $request->page_index,
            'page_size'  => $request->page_size,
            'total'      => $total,
            'list'       => AdminGradeResource::collection($grades)
        ];

        return success($res);
    }

    public function get(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'id' => [
                'required',
                'string',
                Rule::exists('grades', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
        ]);

        $grade = Grade::where('id', $input['id'])->first();

        return success(new AdminGradeResource($grade));
    }

    public function post(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'name' => 'required',
        ]);

        $id = getInputOrDefault($request, 'id', '');
        if ($id) {
            $grade = Grade::where('id', $id)->first();
        } else {
            $grade = new Grade();
        }

        $grade->name = $input['name'];
        $grade->save();

        return success(new AdminGradeResource($grade));
    }
}
