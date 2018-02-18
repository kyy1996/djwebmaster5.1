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

    protected $fileType = [
        'image'    => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'tiff'],
        'audio'    => ['wav', 'mp3', 'ogg', 'wma', 'ape', 'flac', 'm4a'],
        'video'    => ['mp4', 'mkv', 'wmv', 'avi', 'rm', 'mpeg', 'rmvb'],
        'document' => ['doc', 'docx', 'txt', 'md', 'xls', 'xlsx', 'pdf', 'epub', 'ppt', 'pptx'],
        'archive'  => ['zip', 'rar', '7z', 'bz2', 'tar'],
        'other'    => []
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function setUserIdAttr($value = null)
    {
        return $value === null ? User::uid() : $value;
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
        foreach ($this->fileType as $file_type => $extensions)
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
}
