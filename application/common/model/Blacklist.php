<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Blacklist extends Model
{
    use SoftDelete;

    protected $type = [
    ];

    protected $auto = ['operator_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function operatorUser()
    {
        return $this->belongsTo(User::class, 'operator_user_id');
    }

    public function setOperatorUserIdAttr($value = null)
    {
        return $value === null ? User::uid() : $value;
    }
}
