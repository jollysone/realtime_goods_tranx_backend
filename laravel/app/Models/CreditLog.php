<?php

namespace App\Models;

use function App\randomStr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditLog extends Model
{
    const TYPE_BASE = 1;
    const TYPE_BUY  = 2;
    const TYPE_SELL = 3;

    use SoftDeletes;

    public    $incrementing = false;
    protected $hidden       = ['ai', 'deleted_at'];

    public static function getAllTypes()
    {
        return [self::TYPE_BASE, self::TYPE_BUY, self::TYPE_SELL];
    }

    public function save(array $options = [])
    {
        if (!$this->exists && !$this->id) {
            $this->id = randomStr(32);
        }

        return parent::save($options);
    }
}
