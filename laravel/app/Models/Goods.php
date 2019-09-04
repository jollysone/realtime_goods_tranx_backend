<?php

namespace App\Models;

use function App\randomStr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Model
{
    const STATUS_PRIVATE = 0;
    const STATUS_PUBLIC  = 1;

    const SELL_STATUS_AVAILABLE = 1;
    const SELL_STATUS_PENDING   = 2;
    const SELL_STATUS_SOLD      = 3;

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

    public function getPicUrlAttribute()
    {
        if (!$this->pic) {
            return '';
        }
        return $this->pic->url;
    }

    public function getCategoryNameAttribute()
    {
        if (!$this->category) {
            return '';
        }
        return $this->category->name;
    }

    public function getFullCategoryNameAttribute()
    {
        if (!$this->category) {
            return '';
        }
        return sprintf("%s/%s", $this->category->parent->name, $this->category->name);
    }

    public function getFullCategoryIdAttribute()
    {
        if (!$this->category) {
            return '';
        }
        return [$this->category->parent->id, $this->category->id];
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function pic()
    {
        return $this->belongsTo('App\Models\File', 'pic_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
