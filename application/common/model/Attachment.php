<?php

namespace app\common\model;

use Ramsey\Uuid\Uuid;
use think\File;
use think\Model;
use think\model\concern\SoftDelete;

class Attachment extends Model
{
    use SoftDelete;

    const UPLOAD_PATH = 'static/uploads/';

    protected $type = [
        'size' => 'integer',
    ];
    protected $auto = ['uuid', 'type', 'user_id', 'url'];

    protected $file = null;

    protected static $fileType = [
        'image'    => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'tiff', 'svg', 'wmf'],
        'audio'    => ['wav', 'mp3', 'ogg', 'wma', 'ape', 'flac', 'm4a'],
        'video'    => ['mp4', 'mkv', 'wmv', 'avi', 'rm', 'mpeg', 'rmvb'],
        'document' => ['doc', 'docx', 'txt', 'md', 'xls', 'xlsx', 'pdf', 'epub', 'ppt', 'pptx'],
        'archive'  => ['zip', 'rar', '7z', 'bz2', 'tar'],
        'other'    => []
    ];

    /**
     * 初始化处理
     * @access protected
     * @return void
     */
    protected static function init()
    {
        //绑定删除事件
        static::afterDelete(function (Attachment $attachment) {
            //删除记录后删除磁盘上对应的文件
            $attachment->deleteFile();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function photo()
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * 上传一个文件
     * @param File $original_file
     * @return Attachment
     */
    public static function upload(File $original_file)
    {
        $file = $original_file->move(static::UPLOAD_PATH);
        if (!$file) throw new \LogicException($original_file->getError());

        $info = [
            'filename' => $file->getFilename(),
            'path'     => $file->getPathname(),
            'mime'     => $file->getMime(),
            'md5'      => $file->hash('md5'),
            'size'     => $file->getSize(),
        ];

        return static::create($info);
    }

    public function setUuidAttr($value = null)
    {
        return $value === null ? Uuid::uuid5(Uuid::NAMESPACE_DNS, $this->getAttr('md5')) : $value;
    }

    public function setTypeAttr($value = null)
    {
        return $value === null ? $this->getFileType(pathinfo($this->getAttr('filename'), PATHINFO_EXTENSION)) : $value;
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

    public function setUrlAttr($value = null)
    {
        return $value === null ? $this->generateUrl() : $value;
    }

    /**
     * 根据扩展名得到文件类型
     * @param $extension
     * @return string
     */
    private function getFileType($extension)
    {
        $file_type = 'other';
        foreach (static::$fileType as $file_type => $extensions)
            if (in_array($extension, $extensions)) return $file_type;
        return $file_type;
    }

    /**
     * 生成下载URL
     * @return string
     */
    private function generateUrl()
    {
        $base_url = static::UPLOAD_PATH;
        $base_url .= $this->getAttr('filename');
        return $base_url;
    }

    /**
     * 得到附件对应文件对象
     * @return File
     */
    public function getFile()
    {
        $file = $this->file ?: ($this->file = new File($this->getAttr('path')));
        return $file;
    }

    /**
     * 删除文件
     * @return bool
     */
    public function deleteFile()
    {
        $file = $this->getFile();
        if (!$file->isFile() || !$file->isWritable()) return false;
        return unlink($file->getRealPath());
    }
}
