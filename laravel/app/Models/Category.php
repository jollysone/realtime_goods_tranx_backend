<?php

namespace App\Models;

use function App\randomStr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public    $incrementing = false;
    protected $hidden       = ['ai', 'deleted_at'];

    public function save(array $options = [])
    {
        if (!$this->exists && !$this->id) {
            $this->id = randomStr(32);
        }

        return parent::save($options);
    }

    public function increaseGoodsAmount($amount = 1)
    {
        $this->increment('amount', $amount);
        $this->parent->increment('amount', $amount);
    }

    public function decreaseGoodsAmount($amount = 1)
    {
        $this->decrement('amount', $amount);
        $this->parent->decrement('amount', $amount);
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }
}
