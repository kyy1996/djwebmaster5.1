<?php

namespace app\common\model;

use think\Model;
use think\model\Collection;
use think\model\concern\SoftDelete;

class Article extends Model
{
    use SoftDelete;

    const USER_READ   = 1;
    const USER_MODIFY = 2;

    protected $type = [
        'tag'         => 'array',
        'hide'        => 'boolean',
        'mode'        => 'integer',
        'attachments' => 'array',
        'read_count'  => 'integer',
        'extra'       => 'object'
    ];

    protected $insert = ['publish_ip', 'publish_user_id'];
    protected $update = ['update_ip', 'update_user_id'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function parent()
    {
        return $this->belongsTo(Article::class, 'pid');
    }

    public function publishUser()
    {
        return $this->belongsTo(User::class, 'publish_user_id');
    }

    public function updateUser()
    {
        return $this->belongsTo(User::class, 'update_user_id');
    }

    public function isHidden()
    {
        return $this->getAttr('hide');
    }

    public function setHidden()
    {
        return $this->setAttr('hide', true);
    }

    public function setShown()
    {
        return $this->setAttr('hide', false);
    }

    /**
     * 用户是否能阅读
     * @param string $role user-普通用户/guest-游客
     * @return boolean
     */
    public function canRead($role = 'user')
    {
        $mode = $this->getAttr($role . '_mode');
        return !!($mode & static::USER_READ);
    }

    /**
     * 用户是否能修改
     * @param string $role user-普通用户/guest-游客
     * @return boolean
     */
    public function canModify($role = 'user')
    {
        $mode = $this->getAttr($role . '_mode');
        return !!($mode & static::USER_MODIFY);
    }

    /**
     * 获取封面图
     * @return Attachment|null
     * @throws \think\exception\DbException
     */
    public function getCoverImgAttr()
    {
        $value = $this->getAttr('cover_img_id');
        return $value ? Attachment::getOrFail($value) : null;
    }

    /**
     * 获取封面图URL
     * @return null|string
     * @throws \think\exception\DbException
     */
    public function getCoverImgUrlAttr()
    {
        $attachment = $this->getCoverImgAttr();
        return $attachment ? $attachment->getAttr('url') : null;
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
     * 得到分类名称
     * @return string|null
     */
    public function getCategoryNameAttr()
    {
        /** @var Category $category */
        $category = $this->getAttr('category');
        if (!$category) return null;
        return $category->getAttr('name');
    }

    /**
     * 生成用户权限模式
     * @return bool|string
     */
    public function getUserModeAttr()
    {
        $mode      = $this->getAttr('mode');
        $user_mode = substr($mode, 0, 1);
        return $user_mode;
    }

    /**
     * 生成游客权限模式
     * @return bool|string
     */
    public function getGuestModeAttr()
    {
        $mode       = $this->getAttr('mode');
        $guest_mode = substr($mode, 1, 1);
        return $guest_mode;
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

    public function setPublishUserIdAttr($value = null)
    {
        return $value === null ? User::uid() : $value;
    }

    public function setUpdateUserIdAttr($value = null)
    {
        return $value === null ? User::uid() : $value;
    }
}
