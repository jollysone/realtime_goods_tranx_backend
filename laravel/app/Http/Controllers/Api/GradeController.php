<?php

namespace App\Http\Controllers\Api;

use function App\getInputOrDefault;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentListResource;
use App\Http\Resources\GradeListResource;
use App\Models\BrowseLog;
use App\Models\Department;
use App\Models\Grade;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    public function getAll(Request $request): JsonResponse
    {
        $grades = Grade::orderBy('ai', 'asc')->get();

        return success(GradeListResource::collection($grades));
    }
}