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

    /**
     * 关联用户
     * @param null $value
     * @return int|null
     * @throws \think\exception\DbException
     */
    public function setOperatorUserIdAttr($value = null)
    {
        $value = $value ?? User::uid();
        if ($value === null) return null;
        $user = User::getOrFail($value);
        $this->operatorUser()->associate($user);
        return $user->getAttr('uid');
    }

    /**
     * 关联用户
     * @param int $value
     * @return int|null
     * @throws \think\exception\DbException
     */
    public function setUserIdAttr(int $value)
    {
        $user = User::getOrFail($value);
        $this->user()->associate($user);
        return $value;
    }
}
