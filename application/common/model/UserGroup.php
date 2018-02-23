<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class UserGroup extends Model
{
    use SoftDelete;

    protected $type = [
        'status' => 'boolean',
        'uid'    => 'integer'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_group_access', 'user_group_id', 'uid');
    }

    /**
     * @return Menu[]
     * @throws \think\exception\DbException
     */
    public function rules()
    {
        return Menu::all($this->getAttr('rules'));
    }

    public function setRulesAttr($rules = null)
    {
        $rules = $rules ?? $this->getAttr('rules');
        $rules = array_unique($rules);
        return $rules;
    }
}
