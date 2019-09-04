<?php

namespace App\Http\Controllers\Api;

use function App\failure;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileSimpleResource;
use App\Models\File;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FileController extends Controller
{
    public function post(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'type' => ['required', Rule::in(File::getAllTypes())]
        ]);

        $user      = $request->user;
        $uploadRes = File::saveUploadedFile('file', $input['type'], $user->id);
        if ($uploadRes['code'] != ErrorCode::OK) {
            return failure($uploadRes['code'], $uploadRes['msg']);
        }
        $pic = File::constructModel($uploadRes['path']);
        $pic->save();

        return success(new FileSimpleResource($pic));
    }
}