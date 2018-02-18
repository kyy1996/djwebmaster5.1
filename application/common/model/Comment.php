<?php

namespace app\common\model;

use think\Model;
use think\model\Collection;
use think\model\concern\SoftDelete;

class Comment extends Model
{
    use SoftDelete;

    protected $type = [
        'attachments' => 'array',
        'extra'       => 'object'
    ];

    protected $insert = ['publish_ip', 'user_id'];
    protected $update = ['update_ip'];

    public function commentable()
    {
        return $this->morphTo('commentable', [
            'article'  => Article::class,
            'photo'    => Photo::class,
            'activity' => Activity::class,
            'job'      => Job::class
        ]);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'pid');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 得到附件数组属性
     * @param array $attachments
     * @return Attachment[]|false
     * @throws \think\exception\DbException
     */
    public function getAttachmentsAttr($attachments = [])
    {
        return Attachment::all($attachments, 'user');
    }

    /**
     * 设置附件数组属性
     * @param Collection|Attachment[] $attachments
     * @return array
     */
    public function setAttachmentsAttr($attachments = [])
    {
        $attachments = Collection::make($attachments);
        return $attachments->column('id');
    }

    private function setIpAttr($value = null)
    {
        return $value === null ? request()::ip() : $value;
    }

    public function setPublishIpAttr($value)
    {
        return $this->setIpAttr($value);
    }

    public function setUpdateIpAttr($value)
    {
        return $this->setIpAttr($value);
    }

    public function setUserIdAttr($value = null)
    {
        return $value === null ? User::uid() : $value;
    }
}
