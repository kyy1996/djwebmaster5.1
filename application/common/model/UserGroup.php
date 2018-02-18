<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class UserGroup extends Model
{
    use SoftDelete;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_group_access', 'user_group_id', 'uid');
    }
}
