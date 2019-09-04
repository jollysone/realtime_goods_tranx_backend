<?php

namespace App\Http\Controllers\Api;

use function App\getInputOrDefault;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentListResource;
use App\Models\BrowseLog;
use App\Models\Department;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function getAll(Request $request): JsonResponse
    {
        $departments = Department::orderBy('ai', 'asc')->get();

        return success(DepartmentListResource::collection($departments));
    }
}