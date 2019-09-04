<?php

namespace App\Http\Controllers\Api;

use function App\failure;
use function App\getWithPage;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderListResource;
use App\Models\Goods;
use App\Models\Order;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
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
            ],
        ]);

        $goods = Goods::where('id', $input['goods_id'])->first();
        if ($goods->user_id == $user->id) {
            failure(ErrorCode::OPERATION_INVALID, '无法购买自己发布的商品');
        } else if ($goods->status != Goods::STATUS_PUBLIC) {
            failure(ErrorCode::OPERATION_INVALID, '该商品已下线');
        } else if ($goods->sell_status == Goods::SELL_STATUS_PENDING) {
            failure(ErrorCode::OPERATION_INVALID, '该商品暂时无法购买');
        } else if ($goods->sell_status == Goods::SELL_STATUS_SOLD) {
            failure(ErrorCode::OPERATION_INVALID, '该商品已售出');
        }

        $amount = 1;

        $order                  = new Order();
        $order->sn              = Order::generateSn();
        $order->buyer_id        = $user->id;
        $order->seller_id       = $goods->user->id;
        $order->goods_id        = $goods->id;
        $order->amount          = $amount;
        $order->price           = $goods->price * $amount;
        $order->status          = Order::STATUS_PROCESSING;
        $order->will_timeout_at = now()->addMinutes(Order::PENDING_MINUTES);
        $order->save();

        $goods->sell_status = Goods::SELL_STATUS_PENDING;
        $goods->save();

        return success(new OrderListResource($order));
    }

    public function getMany(Request $request): JsonResponse
    {
        $user   = $request->user;
        $orders = Order::where('buyer_id', $user->id)->orderBy('id', 'desc');

        $total  = $orders->count();
        $orders = getWithPage($orders);

        $res = [
            'page_index' => $request->page_index,
            'page_size'  => $request->page_size,
            'total'      => $total,
            'list'       => OrderListResource::collection($orders)
        ];

        return success($res);
    }

    public function getManyOfSell(Request $request): JsonResponse
    {
        $user   = $request->user;
        $orders = Order::where('seller_id', $user->id)->orderBy('id', 'desc');

        $total  = $orders->count();
        $orders = getWithPage($orders);

        $res = [
            'page_index' => $request->page_index,
            'page_size'  => $request->page_size,
            'total'      => $total,
            'list'       => OrderListResource::collection($orders)
        ];

        return success($res);
    }

    public function get(Request $request): JsonResponse
    {
        $user  = $request->user;
        $input = $this->validate($request, [
            'sn' => [
                'required',
                Rule::exists('orders', 'sn')->where(function ($query) use ($user) {
                    $query->whereNull('deleted_at')->where(function ($query) use ($user) {
                        $query->orWhere('buyer_id', $user->id);
                        $query->orWhere('seller_id', $user->id);
                    });
                })
            ],
        ]);

        $order = Order::where('sn', $input['sn'])->first();

        return success(new OrderDetailResource($order));
    }

    public function putCancel(Request $request): JsonResponse
    {
        $user  = $request->user;
        $input = $this->validate($request, [
            'sn' => [
                'required',
                Rule::exists('orders', 'sn')->where(function ($query) use ($user) {
                    $query->whereNull('deleted_at')->where(function ($query) use ($user) {
                        $query->orWhere('buyer_id', $user->id);
                        $query->orWhere('seller_id', $user->id);
                    });
                })
            ],
        ]);
        /**
         * @var $order Order
         */
        $order = Order::where('sn', $input['sn'])->first();
        if ($order->status != Order::STATUS_PROCESSING) {
            return failure(ErrorCode::OPERATION_INVALID, '此订单无法取消');
        }

        $order->setStatus(Order::STATUS_CANCELED);

        return success(new OrderDetailResource($order));
    }

    public function putDone(Request $request): JsonResponse
    {
        $user  = $request->user;
        $input = $this->validate($request, [
            'sn' => [
                'required',
                Rule::exists('orders', 'sn')->where(function ($query) use ($user) {
                    $query->whereNull('deleted_at')->where(function ($query) use ($user) {
                        $query->orWhere('seller_id', $user->id);
                    });
                })
            ],
        ]);

        $order = Order::where('sn', $input['sn'])->first();
        if ($order->status != Order::STATUS_PROCESSING) {
            return failure(ErrorCode::OPERATION_INVALID, '此订单无法设置为交易成功');
        }

        $order->setStatus(Order::STATUS_DONE);

        return success(new OrderDetailResource($order));
    }

    public function putExtendTimeout(Request $request): JsonResponse
    {
        $user  = $request->user;
        $input = $this->validate($request, [
            'sn'      => [
                'required',
                Rule::exists('orders', 'sn')->where(function ($query) use ($user) {
                    $query->whereNull('deleted_at')->where(function ($query) use ($user) {
                        $query->orWhere('seller_id', $user->id);
                    });
                })
            ],
            'minutes' => 'required|integer|min:1'
        ]);

        /**
         * @var $order Order
         */
        $order = Order::where('sn', $input['sn'])->first();
        if ($order->status != Order::STATUS_PROCESSING) {
            return failure(ErrorCode::OPERATION_INVALID, '此订单无法延长交易时间');
        }

        $order->will_timeout_at = $order->will_timeout_at->addMinutes($input['minutes']);
        $order->save();

        return success(new OrderDetailResource($order));
    }
}