<?php

namespace App\Models;

use function App\randomStr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends Model
{
    use SoftDeletes;

    public    $incrementing = false;
    protected $hidden       = ['ai', 'deleted_at'];
    protected $casts        = ['base_score' => 'integer', 'buy_score' => 'integer', 'sell_score' => 'integer'];

    public function save(array $options = [])
    {
        if (!$this->exists && !$this->id) {
            $this->id = randomStr(32);
        }

        return parent::save($options);
    }

    public function logs()
    {
        return $this->hasMany('App\Models\CreditLog', 'user_id', 'user_id');
    }

    public function change($type, int $change, string $remark = '')
    {
        if (!in_array($type, CreditLog::getAllTypes()) || $change == 0) {
            return;
        }

        switch ($type) {
            case CreditLog::TYPE_BASE:
                {
                    $typeColumnName = 'base_score';
                    break;
                }
            case CreditLog::TYPE_BUY:
                {
                    $typeColumnName = 'buy_score';
                    break;
                }
            case CreditLog::TYPE_SELL:
                {
                    $typeColumnName = 'sell_score';
                    break;
                }
            default:
                {
                    $typeColumnName = '';
                }
        }

        $oldScore = $this->$typeColumnName;
        if ($change > 0) {
            $this->increment($typeColumnName, $change);
        } else {
            $this->decrement($typeColumnName, $change * -1);
        }
        $this->save();

        $log               = new CreditLog();
        $log->user_id      = $this->user_id;
        $log->type         = $type;
        $log->old_score    = $oldScore;
        $log->change_score = $change;
        $log->remark       = $remark;
        $log->save();
    }
}
