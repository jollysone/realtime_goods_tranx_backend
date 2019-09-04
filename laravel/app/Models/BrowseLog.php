<?php

namespace App\Models;

use function App\randomStr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrowseLog extends Model
{
    use SoftDeletes;

    public    $incrementing = false;
    protected $hidden       = ['ai', 'deleted_at'];
    protected $fillable     = ['is_last'];

    public function save(array $options = [])
    {
        if (!$this->exists && !$this->id) {
            $this->id = randomStr(32);
        }

        return parent::save($options);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withTrashed();
    }

    public function goods()
    {
        return $this->belongsTo('App\Models\Goods', 'goods_id')->withTrashed();
    }
}
