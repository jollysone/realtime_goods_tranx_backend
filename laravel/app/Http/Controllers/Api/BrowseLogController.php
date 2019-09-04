<?php

namespace App\Http\Controllers\Api;

use function App\failure;
use function App\getInputOrDefault;
use function App\getWithPage;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrowseLogListResource;
use App\Models\BrowseLog;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrowseLogController extends Controller
{
    public function post(Request $request): JsonResponse
    {
        $user  = $request->user;
        $input = $this->validate($request, [
            'goods_id' => [
                'required',
                Rule::exists('goods', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ]
        ]);

        BrowseLog::where('user_id', $user->id)
                 ->where('goods_id', $input['goods_id'])
                 ->where('is_last', 1)
                 ->update([
                     'is_last' => 0
                 ]);

        $log           = new BrowseLog();
        $log->user_id  = $user->id;
        $log->goods_id = $input['goods_id'];
        $log->source   = getInputOrDefault($request, 'source');
        $log->is_last  = 1;
        $log->save();

        return success(['id' => $log->id]);
    }

    public function put(Request $request): JsonResponse
    {
        $user  = $request->user;
        $input = $this->validate($request, [
            'id' => [
                'required',
                Rule::exists('browse_logs', 'id')->where(function ($query) use ($user) {
                    $query->whereNull('deleted_at')->where('user_id', $user->id);
                })
            ]
        ]);

        $log = BrowseLog::where('id', $input['id'])->first();
        if (!$log->is_last) {
            return failure(ErrorCode::RESOURCE_NOT_EXIST, '无效 ID');
        }
        $log->stay_time = now()->diffInSeconds($log->created_at);
        $log->save();
        return success();
    }

    public function getMany(Request $request): JsonResponse
    {
        $user = $request->user;
        $logs = BrowseLog::where('user_id', $user->id)->where('is_last', 1)->orderBy('ai', 'desc');

        $total = $logs->count();
        $logs  = getWithPage($logs);

        $res = [
            'page_index' => $request->page_index,
            'page_size'  => $request->page_size,
            'total'      => $total,
            'list'       => BrowseLogListResource::collection($logs)
        ];

        return success($res);
    }
}