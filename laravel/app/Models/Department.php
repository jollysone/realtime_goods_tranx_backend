<?php

namespace App\Models;

use function App\randomStr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
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
}
