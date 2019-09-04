<?php

namespace App\Models;

use function App\randomStr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    const ROLE_TYPE_NORMAL = 1;
    const ROLE_TYPE_ADMIN  = 2;

    const STATUS_FREEZE = 0;
    const STATUS_NORMAL = 1;

    use SoftDeletes;

    public    $incrementing = false;
    protected $hidden       = ['ai', 'deleted_at'];

    public $hidePhone = false;

    public function save(array $options = [])
    {
        if (!$this->exists && !$this->id) {
            $this->id = randomStr(32);
        }

        return parent::save($options);
    }

    public function getLoginToken(string $appType): Token
    {
        $token          = Token::generate($appType, $this->id);
        $this->login_at = now();
        $this->save();
        return $token;
    }

    public function credit()
    {
        return $this->hasOne('App\Models\Credit', 'user_id');
    }

    public function changeCredit($type, int $change, string $remark = '')
    {
        $this->credit->change($type, $change, $remark);
    }
}
