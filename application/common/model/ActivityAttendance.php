<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class ActivityAttendance extends Model
{
    use SoftDelete;

    protected $type = [
        'valid' => 'boolean'
    ];

    protected $auto = ['ip', 'ua', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function setInvalid()
    {
        return $this->setAttr('valid', false);
    }

    public function setValid()
    {
        return $this->setAttr('valid', true);
    }

    public function setIpAttr($value = null)
    {
        return $value === null ? request()::ip() : $value;
    }

    public function setUaAttr($value = null)
    {
        return $value === null ? request()::server('HTTP_USER_AGENT') : $value;
    }

    public function setUserIdAttr($value = null)
    {
        return $value === null ? User::uid() : $value;
    }
}
