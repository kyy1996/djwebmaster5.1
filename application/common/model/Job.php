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

    public function setUserIdAttr($value = null)
    {
        return $value === null ? User::uid() : $value;
    }
}
