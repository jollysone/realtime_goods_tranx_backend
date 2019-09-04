<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class Order
 * @package App\Models
 * @property Carbon $will_timeout_at
 */
class Order extends Model
{
    const PENDING_MINUTES = 30;

    const STATUS_PROCESSING = 1;
    const STATUS_DONE       = 2;
    const STATUS_CANCELED   = 3;
    const STATUS_TIMEOUT    = 4;

    use SoftDeletes;

    protected $hidden = ['id', 'deleted_at'];
    protected $casts  = ['will_timeout_at' => 'datetime'];

    static public function generateSn()
    {
        $res = '';
        DB::transaction(function () use (&$res) {
            $config = Config::where('key', 'order_sn')->lockForUpdate()->first();

            $time = time();
            if (!$config) {
                $config         = new Config();
                $config->key    = 'order_sn';
                $config->value  = date("YmdHis000", $time);
                $config->remark = '最新订单号';
            }

            $orderSn      = $config->value;
            $theSecondNow = date("YmdHis000", $time);

            if ($theSecondNow > $orderSn) {
                $orderSn = date("YmdHis001", $time);
            } else {
                $count = (int)substr($orderSn, 14, 3);
                $count++;
                $orderSn = sprintf("%s%03d", date("YmdHis", $time), $count);
            }

            $res           = $orderSn;
            $config->value = $res;
            $config->save();
        });

        return $res;
    }

    public function buyer()
    {
        return $this->belongsTo('App\Models\User', 'buyer_id')->withTrashed();
    }

    public function seller()
    {
        return $this->belongsTo('App\Models\User', 'seller_id')->withTrashed();
    }

    public function goods()
    {
        return $this->belongsTo('App\Models\Goods', 'goods_id')->withTrashed();
    }

    public function setStatus($status)
    {
        switch ($status) {
            case Order::STATUS_DONE:
                {
                    $this->status = Order::STATUS_DONE;
                    $this->save();

                    $this->goods->sell_status = Goods::SELL_STATUS_SOLD;
                    $this->goods->save();

                    $creditChange = intval($this->price / 10);
                    $this->buyer->changeCredit(CreditLog::TYPE_BUY, $creditChange, sprintf("完成交易（单号%s）", $this->sn));
                    $this->seller->changeCredit(CreditLog::TYPE_SELL, $creditChange, sprintf("完成交易（单号%s）", $this->sn));

                    break;
                }
            case Order::STATUS_CANCELED:
                {
                    $this->status = $status;
                    $this->save();

                    $this->goods->sell_status = Goods::SELL_STATUS_AVAILABLE;
                    $this->goods->save();

                    break;
                }
        }
    }
}
