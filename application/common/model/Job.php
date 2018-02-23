<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Job extends Model
{
    use SoftDelete;

    protected $auto = ['user_id'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_id');
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
}
