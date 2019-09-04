<?php

namespace App\Http\Controllers\Api;

use function App\failure;
use function App\getInputOrDefault;
use function App\getWithPage;
use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\GoodsDetailResource;
use App\Http\Resources\GoodsListResource;
use App\Models\BrowseLog;
use App\Models\Category;
use App\Models\Goods;
use function App\success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GoodsController extends Controller
{
    public function post(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'title'       => 'required',
            'price'       => 'required',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')->where('level', 2);
                })
            ],
            'pic_id'      => 'required',
            'description' => 'required',
        ]);

        $user = $request->user;

        $goods              = new Goods();
        $goods->user_id     = $user->id;
        $goods->title       = $input['title'];
        $goods->price       = $input['price'];
        $goods->category_id = $input['category_id'];
        $goods->pic_id      = $input['pic_id'];
        $goods->description = $input['description'];
        $goods->status      = Goods::STATUS_PUBLIC;
        $goods->sell_status = Goods::SELL_STATUS_AVAILABLE;
        $goods->save();

        Category::where('id', $input['category_id'])->first()->increaseGoodsAmount(1);

        return success(new GoodsDetailResource($goods));
    }

    public function put(Request $request): JsonResponse
    {
        $user = $request->user;

        $input = $this->validate($request, [
            'id'          => [
                'required',
                Rule::exists('goods', 'id')->where(function ($query) use ($user) {
                    $query->whereNull('deleted_at')->where('user_id', $user->id);
                })
            ],
            'title'       => 'required',
            'price'       => 'required',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')->where('level', 2);
                })
            ],
            'pic_id'      => 'required',
            'description' => 'required',
        ]);

        $goods = Goods::where('id', $input['id'])->first();
        if ($goods->sell_status != Goods::SELL_STATUS_AVAILABLE) {
            return failure(ErrorCode::OPERATION_INVALID, '无法编辑此商品');
        }

        Category::where('id', $goods->category_id)->first()->decreaseGoodsAmount(1);

        $goods->title       = $input['title'];
        $goods->price       = $input['price'];
        $goods->category_id = $input['category_id'];
        $goods->pic_id      = $input['pic_id'];
        $goods->description = $input['description'];
        $goods->status      = Goods::STATUS_PUBLIC;
        $goods->save();

        Category::where('id', $goods->category_id)->first()->increaseGoodsAmount(1);

        return success(new GoodsDetailResource($goods));
    }

    public function getManyOfMine(Request $request): JsonResponse
    {
        $user  = $request->user;
        $goods = Goods::where('user_id', $user->id);

        $goods = $goods->orderBy('created_at', 'desc');

        $total = $goods->count();
        $goods = getWithPage($goods);

        $res = [
            'page_index' => $request->page_index,
            'page_size'  => $request->page_size,
            'total'      => $total,
            'list'       => GoodsListResource::collection($goods)
        ];

        return success($res);
    }

    public function getMany(Request $request): JsonResponse
    {
        $categoryId = getInputOrDefault($request, 'category_id', '');
        $keywords   = getInputOrDefault($request, 'keywords', '');
        $keywords   = preg_replace("/\s+/", ' ', $keywords);
        $keywords   = preg_replace("/^\s/", '', $keywords);
        $keywords   = preg_replace("/\s$/", '', $keywords);
        if ($keywords) {
            $keywords = explode(' ', $keywords);
        } else {
            $keywords = [];
        }

        $goods = Goods::where('status', Goods::STATUS_PUBLIC);
        if (count($keywords)) {
            $goods = $goods->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where(function ($query) use ($keyword) {
                        $query->orWhere('title', 'like', '%' . $keyword . '%');
                    });
                }
            });
        }

        if ($categoryId) {
            $goods = $goods->where('category_id', $categoryId);
        }

        $goods = $goods->orderBy('updated_at', 'desc');
        $total = $goods->count();
        $goods = getWithPage($goods);

        $res = [
            'page_index' => $request->page_index,
            'page_size'  => $request->page_size,
            'total'      => $total,
            'list'       => GoodsListResource::collection($goods)
        ];

        return success($res);
    }

    public function get(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'id' => [
                'required',
                Rule::exists('goods', 'id')
            ],
        ]);

        $goods = Goods::where('id', $input['id'])->withTrashed()->first();

        return success(new GoodsDetailResource($goods));
    }

    public function delete(Request $request): JsonResponse
    {
        $user = $request->user;

        $input = $this->validate($request, [
            'id' => [
                'required',
                Rule::exists('goods', 'id')->where(function ($query) use ($user) {
                    $query->whereNull('deleted_at')->where('user_id', $user->id);
                })
            ],
        ]);

        $goods = Goods::where('id', $input['id'])->first();
        Category::where('id', $goods->category_id)->first()->decreaseGoodsAmount(1);
        $goods->delete();

        return success();
    }

    /**
     * 推荐商品
     * <br/>
     * 根据近期浏览最多的3个商品的类别，推荐同类别的5个商品(不含前面的3个商品)
     * @param Request $request
     * @return JsonResponse
     */
    public function getRecommend(Request $request): JsonResponse
    {
        $user = $request->user;

        $logs = BrowseLog::where('user_id', $user->id)->where('created_at', '>=', now()->subDays(7))
                         ->select('goods_id', DB::raw('COUNT(*) as browse_count'))
                         ->groupBy('goods_id')
                         ->orderBy('browse_count', 'desc')
                         ->take(3)
                         ->get();

        $categoryIds     = [];
        $excludeGoodsIds = [];
        foreach ($logs as $log) {
            $categoryIds[]     = $log->goods->category->id;
            $excludeGoodsIds[] = $log->goods->id;
        }

        $excludeGoodsId = getInputOrDefault($request, 'exclude_goods_id');
        if ($excludeGoodsId) {
            $excludeGoodsIds[] = $excludeGoodsId;
        }

        $goods = Goods::whereIn('category_id', $categoryIds)->whereNotIn('id', $excludeGoodsIds)->orderBy('updated_at', 'desc');

        $goods = $goods->take(5)->get();

        return success(GoodsListResource::collection($goods));
    }
}