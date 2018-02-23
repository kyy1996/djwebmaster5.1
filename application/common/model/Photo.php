<?php

namespace app\common\model;

use think\File;
use think\Model;
use think\model\concern\SoftDelete;

class Photo extends Model
{
    use SoftDelete;

    protected $type = [
        'hide' => 'boolean'
    ];
    protected $auto = ['ip', 'user_id'];


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
        return $this->belongsTo(Attachment::class, 'attachment_id')->bind(['url']);
    }

    public function thumbnail()
    {
        return $this->belongsTo(Attachment::class, 'thumbnail_id')->bind([
            'url' => 'thumbnail_url'
        ]);
    }

    /**
     * 上传一张图片
     * @param File $file
     * @return false|Photo
     */
    public static function upload(File $file)
    {
        $attachment = Attachment::upload($file);
        /** @var Photo|false $photo */
        $photo = $attachment->photo()->save($attachment);
        //上传失败则删除附件记录
        if (!$photo) $attachment->delete(true);
        $photo->thumbnail()->associate($attachment);
        return $photo;
    }

    public function setIpAttr($value = null)
    {
        return $value === null ? request()::ip() : $value;
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
