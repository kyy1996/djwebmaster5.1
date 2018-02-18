<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class User extends Model
{
    use SoftDelete;

    protected $pk = 'uid';

    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'user_group_access', 'user_group_id', 'uid');
    }

    public static function uid()
    {
        //TODO
        return 0;
    }
}
