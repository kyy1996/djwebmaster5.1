<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class JobApplication extends Model
{
    use SoftDelete;

    const STATUS_APPLIED  = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = -1;

    protected $type = [
        'status' => 'integer'
    ];

    protected $auto = ['ip', 'ua'];

    protected $insert = ['user_id'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function operatorUser()
    {
        return $this->belongsTo(User::class, 'operator_user_id');
    }

    public function getStatusTextAttr()
    {
        switch ($this->getAttr('status')) {
            case static::STATUS_APPLIED:
                return lang('已申请');
                break;
            case static::STATUS_APPROVED:
                return lang('已通过');
                break;
            case static::STATUS_REJECTED:
                return lang('已拒绝');
                break;
        }
        return lang('未知');
    }

    public function setIpAttr($value = null)
    {
        return $value === null ? request()::ip() : $value;
    }

    public function setUaAttr($value = null)
    {
        return $value === null ? request()::server('HTTP_USER_AGENT') : $value;
    }

    /**
     * 关联用户
     * @param null $value
     * @return int|null
     * @throws \think\exception\DbException
     */
    public function setUserIdAttr($value = null)
    {
        $value = $value ?? User::uid();
        if ($value === null) return null;
        $user = User::getOrFail($value);
        $this->user()->associate($user);
        return $user->getAttr('uid');
    }

    public function approve($reason = '')
    {
        $this->setAttr('status', static::STATUS_APPROVED);
        $this->setAttr('reason', $reason);
        $this->setAttr('operator_user_id', User::uid());
        return $this->save();
    }

    public function reject($reason = '')
    {
        $this->setAttr('status', static::STATUS_REJECTED);
        $this->setAttr('reason', $reason);
        $this->setAttr('operator_user_id', User::uid());
        return $this->save();
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
}
