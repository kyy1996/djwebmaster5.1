<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Category extends Model
{
    use SoftDelete;

    public function parent()
    {
        return $this->belongsTo(static::class, 'pid');
    }

    public function article()
    {
        return $this->hasMany(Article::class);
    }

    public function children()
    {
        return $this->hasMany(static::class, 'pid');
    }
}
