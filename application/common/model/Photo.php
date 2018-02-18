<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Photo extends Model
{
    use SoftDelete;

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attachment()
    {
        return $this->belongsTo(Attachment::class, 'attachment_id');
    }

    public function thumbnail()
    {
        return $this->belongsTo(Attachment::class, 'thumbnail_id');
    }
}
