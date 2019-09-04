<?php

namespace App\Http\Controllers\Api\Admin;

use function App\failure;
use function App\getInputOrDefault;
use function App\getWithPage;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminCategoryListResource;
use App\Http\Resources\AdminDepartmentResource;
use App\Http\Resources\AdminGradeResource;
use App\Http\Resources\AdminOrderResource;
use App\Http\Resources\AdminUserResource;
use App\Models\Category;
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

class CategoryController extends Controller
{
    public function getMany(Request $request): JsonResponse
    {
        $level          = getInputOrDefault($request, 'level', 1);
        $isWithChildren = getInputOrDefault($request, 'is_with_children', 1);
        $name           = getInputOrDefault($request, 'name');

        $categories = Category::where('level', $level)->orderBy('ai', 'asc');

        if ($name) {
            $categories = $categories->where('name', 'like', '%' . $name . '%');
        }

        $total      = $categories->count();
        $categories = getWithPage($categories);

        AdminCategoryListResource::$isWithChildren = $isWithChildren;

        $res = [
            'page_index' => $request->page_index,
            'page_size'  => $request->page_size,
            'total'      => $total,
            'list'       => AdminCategoryListResource::collection($categories)
        ];

        return success($res);
    }

    public function get(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'id' => [
                'required',
                'string',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
        ]);

        $category = Category::where('id', $input['id'])->first();

        return success(new AdminCategoryListResource($category));
    }

    public function post(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'name' => 'required',
        ]);

        $id = getInputOrDefault($request, 'id', '');
        if ($id) {
            $category = Category::where('id', $id)->first();
        } else {
            $category = new Category();
        }

        $category->name      = $input['name'];
        $category->parent_id = getInputOrDefault($request, 'parent_id');
        if ($category->parent_id) {
            $category->level = 2;
        } else {
            $category->level = 1;
        }
        $category->save();

        return success(new AdminCategoryListResource($category));
    }
}
