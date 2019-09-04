<?php

namespace App\Http\Controllers\Api\Admin;

use function App\failure;
use function App\getInputOrDefault;
use function App\getWithPage;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminOrderResource;
use App\Http\Resources\AdminUserResource;
use App\Models\Credit;
use App\Models\Order;
use App\Models\User;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function getMany(Request $request): JsonResponse
    {
        $status = getInputOrDefault($request, 'status');

        $orders = Order::whereNull('deleted_at');

        if ($status) {
            $orders = $orders->where('status', $status);
        }

        $total  = $orders->count();
        $orders = getWithPage($orders);

        $res = [
            'page_index' => $request->page_index,
            'page_size'  => $request->page_size,
            'total'      => $total,
            'list'       => AdminOrderResource::collection($orders)
        ];

        return success($res);
    }

    public function delete(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'sn' => [
                'required',
                Rule::exists('orders', 'sn')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
        ]);

        $order = Order::where('sn', $input['sn'])->first();
        if ($order->status == Order::STATUS_PROCESSING) {
            $order->setStatus(Order::STATUS_CANCELED);
        }
        $order->delete();

        return success();
    }
}
