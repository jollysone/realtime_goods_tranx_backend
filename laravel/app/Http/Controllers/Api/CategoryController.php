<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryListResource;
use App\Models\Category;
use function App\success;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function getAll(): JsonResponse
    {
        $list = Category::where('level', 1)
                           ->orderBy('ai', 'asc')
                           ->get();

        return success(CategoryListResource::collection($list));
    }
}