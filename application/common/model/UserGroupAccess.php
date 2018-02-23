<?php

namespace app\common\model;

use think\Model;

class UserGroupAccess extends Model
{
    protected $pk = ['uid', 'user_group_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }

    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class);
    }
}
