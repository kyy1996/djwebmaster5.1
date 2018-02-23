<?php

namespace app\common\model;

use app\common\authentication\AuthenticateUser;
use app\common\authentication\AuthenticationManager;
use ExtraModel\HasManyThroughBelong;
use think\exception\DbException;
use think\Model;
use think\model\concern\SoftDelete;
use think\model\relation\HasOne;

class User extends Model
{
    use SoftDelete;
    use AuthenticateUser;

    /** @var User 已登录用户模型 */
    protected static $loginUser = null;

    protected $pk = 'uid';

    protected $type = [
        'uid'    => 'integer',
        'status' => 'boolean',
        'admin'  => 'boolean'
    ];
    protected $auto = ['ip'];


    /**
     * 用户组
     * @return \think\model\relation\BelongsToMany
     */
    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'user_group_access', 'user_group_id', 'uid');
    }

    /**
     * 用户个人信息
     * @return HasOne
     */
    public function userProfile()
    {
        return $this->hasOne(UserProfile::class, 'uid')->bind(['student_no', 'school', 'class', 'name']);
    }

    /**
     * 活动参与信息
     * @return \think\model\relation\HasMany
     */
    public function activityEnrollments()
    {
        return $this->hasMany(ActivityEnrollment::class);
    }

    /**
     * 活动出席情况
     * @return \think\model\relation\HasMany
     */
    public function activityAttendances()
    {
        return $this->hasMany(ActivityAttendance::class);
    }

    /**
     * 与用户相关的活动
     * @return HasManyThroughBelong
     */
    public function relevantActivities(): HasManyThroughBelong
    {
        // 记录当前关联信息
        $model      = $this->parseModel(Activity::class);
        $through    = $this->parseModel(ActivityAttendance::class);
        $localKey   = $this->getPk();
        $foreignKey = $this->getForeignKey($this->name);
        $throughKey = 'activity_id';

        return new HasManyThroughBelong($this, $model, $through, $foreignKey, $throughKey, $localKey);
    }

    /**
     * 用户主持的活动
     * @return array|\think\model\Collection
     * @throws DbException
     */
    public function hostedActivities()
    {
        return Activity::getActivitiesByLecturers([$this->getAttr('uid')]);
    }

    /**
     * 用户被加入黑名单的信息
     * @return HasOne
     */
    public function blacklisted()
    {
        return $this->hasOne(Blacklist::class);
    }

    /**
     * 用户添加的黑名单信息
     * @return \think\model\relation\HasMany
     */
    public function blacklists()
    {
        return $this->hasMany(Blacklist::class, 'operator_user_id');
    }

    /**
     * 用户的评论
     * @return \think\model\relation\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 用户发布的职位信息
     * @return \think\model\relation\HasMany
     */
    public function publishedJobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * 用户申请的职位
     * @return \think\model\relation\HasMany
     */
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * 用户发布的文章
     * @return \think\model\relation\HasMany
     */
    public function publishArticles()
    {
        return $this->hasMany(Article::class, 'publish_user_id');
    }

    /**
     * 用户修改过的文章
     * @return \think\model\relation\HasMany
     */
    public function updateArticles()
    {
        return $this->hasMany(Article::class, 'update_user_id');
    }

    /**
     * 用户发布的照片
     * @return \think\model\relation\HasMany
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * 用户上传的附件
     * @return \think\model\relation\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * 得到已登录用户UID
     * @return integer|null
     */
    public static function uid()
    {
        return ($user = static::user()) ? $user->getAttr('uid') : null;
    }

    /**
     * 得到当前已登录用户模型
     * @return User|array|null|\PDOStatement|string|Model
     */
    public static function user()
    {
        try {
            /** @var AuthenticationManager $auth */
            $auth = app('authentication');
            $uid  = $auth->guard()->id();
            return static::$loginUser ?: (static::$loginUser = static::getOrFail($uid, ['user_groups', 'user_profile'], true));
        } catch (DbException $e) {
        }
        return null;
    }


    public function setIpAttr($value = null)
    {
        return $value === null ? request()::ip() : $value;
    }

    /**
     * 用户是否被加入黑名单
     * @return bool
     */
    public function isBlacklisted()
    {
        return !!$this->getAttr('blacklisted');
    }
}
