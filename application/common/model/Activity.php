<?php

namespace app\common\model;

use alen\helper\Datetime;
use think\Model;
use think\model\concern\SoftDelete;

class Activity extends Model
{
    use SoftDelete;

    const ACTIVITY_HIDDEN = 1;
    const ACTIVITY_PAUSED = 2;

    protected $type = [
        'tag'            => 'array',
        'lecturers'      => 'json',
        'locations'      => 'json',
        'cycle_interval' => 'integer',
        'capacity'       => 'integer',
        'token_amount'   => 'integer',//已选课人数
        'status'         => 'integer',
        'extra'          => 'object',
        'start_time'     => 'datetime',
        'end_time'       => 'datetime',
    ];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function attendances()
    {
        return $this->hasMany(ActivityAttendance::class, 'activity_id');
    }

    public function enrollments()
    {
        return $this->hasMany(ActivityEnrollment::class, 'activity_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function isHidden()
    {
        return $this->getAttr('status') & static::ACTIVITY_HIDDEN;
    }

    public function isPaused()
    {
        return $this->getAttr('status') & static::ACTIVITY_PAUSED;
    }

    public function setPaused()
    {
        return $this->setAttr('status', $this->getAttr('status') | static::ACTIVITY_PAUSED);
    }

    public function setHidden()
    {
        return $this->setAttr('status', $this->getAttr('status') | static::ACTIVITY_HIDDEN);
    }

    public function isNormal()
    {
        return !($this->getAttr('status') & (static::ACTIVITY_HIDDEN | static::ACTIVITY_PAUSED));
    }

    public function getLectureNameAttr()
    {
        $lecturers = $this->getAttr('lecturers');
        $name      = [];
        foreach ($lecturers as $lecturer) {
            $name[] = $lecturer->name;
        }
        return $name;
    }

    public function getLectureUidAttr()
    {
        $lecturers = $this->getAttr('lecturers');
        $uid       = [];
        foreach ($lecturers as $lecturer) {
            $uid[] = $lecturer->uid;
        }
        return $uid;
    }

    public function getFirstLecturerNameAttr()
    {
        if (($lecturers = $this->getAttr('lecturer_name')) && ($name = $lecturers[0]->name)) return $name;
        return null;
    }

    public function getFirstLecturerUidAttr()
    {
        if (($lecturers = $this->getAttr('lecturer_uid')) && ($id = $lecturers[0]->name)) return $id;
        return null;
    }

    protected function getLocationNameAttr()
    {
        $locations = $this->getAttr('locations');
        $name      = [];
        foreach ($locations as $location) {
            $name[] = $location->name;
        }
        return $name;
    }

    public function getLocationIdAttr()
    {
        $locations = $this->getAttr('locations');
        $id        = [];
        foreach ($locations as $location) {
            $id[] = $location->uid;
        }
        return $id;
    }

    public function getFirstLocationNameAttr()
    {
        if (($locations = $this->getAttr('location_name')) && ($name = $locations[0]->name)) return $name;
        return null;
    }

    public function getFirstLocationIdAttr()
    {
        if (($locations = $this->getAttr('location_id')) && ($id = $locations[0]->name)) return $id;
        return null;
    }

    /**
     * 得到指定日期之后一周内的活动开始时间
     * @param null|string|integer $date     查询日期或者时间戳，默认为今天
     * @param int                 $duration 查询天数，默认七天
     * @return array 日期数组
     */
    public function getStartTime($date = null, $duration = 7)
    {
        $activity_start_time_set = [];
        $date                    = is_integer($date) ? date('Y-m-d', $date) : ($date ?: date('Y-m-d'));
        $activity_time           = strtotime($this->getAttr('start_time'));
        $week_start_time         = strtotime($date);

        for ($i = 0; $i < $duration; $i++) {
            $current_time = $week_start_time + $i * 86400;
            if (!$this->isStartTime($current_time)) continue;
            $current_date = date('Y-m-d ', $current_time);
            $start_time   = date('H:i', $activity_time);

            $activity_start_time_set[] = $current_date . $start_time;
        }

        return $activity_start_time_set;
    }

    /**
     * 判断某个日期或时间是否是活动开始时间
     * @param int|string $time    时间或时间戳
     * @param bool       $precise 是否精确到时间
     * @return bool
     */
    public function isStartTime($time = null, $precise = false)
    {
        $current_time          = is_integer($time) ? date('Y-m-d H:i', $time) : ($time ?: date('Y-m-d H:i'));
        $cycle_start_time      = strtotime($this->getAttr('start_time'));
        $cycle_end_time        = strtotime($this->getAttr('end_time'));
        $cycle_start_day_time  = Datetime::dayFromTime($cycle_start_time)[0];
        $cycle_type            = $this->getAttr('cycle_type');
        $cycle_interval_factor = $this->getAttr('cycle_interval');
        //活动已结束或还未开始
        if ($cycle_start_time > $current_time || $cycle_end_time < $current_time) return false;
        if (true === $precise && date('H:i', strtotime($current_time)) !== date('H:i', $cycle_start_time)) return false;

        if ('week' === $cycle_type || 'day' === $cycle_type) {
            //周/天循环，天*间隔 为事件循环周期
            $cycle_interval       = $this->getCycleInterval($cycle_type);
            $event_cycle_interval = $cycle_interval * ($cycle_interval_factor + 1);
            $different_time       = $current_time - $cycle_start_day_time;
            $times                = $different_time / $event_cycle_interval;
            //若整除则这一天为活动举办时间
            return is_integer($times);
        } else {
            //其他循环，则直接判断日期
            $cycle_start_year  = date('Y', $cycle_start_time);
            $cycle_start_month = date('m', $cycle_start_time);
            $cycle_start_day   = date('d', $cycle_start_time);

            $is_hold = false;
            switch ($cycle_type) {
                case 'month':
                    //每n月循环
                    $current_month = date('m', $current_time);
                    $current_day   = date('d', $current_time);
                    //日期不一致，则直接排除
                    if ($current_day != $cycle_start_day) break;
                    $current_month += 12;
                    //n = (month + 12 - start) % 12
                    $difference = ($current_month - $cycle_start_month) % 12;
                    $is_hold    = $difference / ($cycle_interval_factor + 1);
                    $is_hold    = is_int($is_hold);
                    //整除则为举办月，且为举办日
                    break;
                case 'year':
                    //每n年循环
                    $current_year  = date('Y', $current_time);
                    $current_month = date('m', $current_time);
                    $current_day   = date('d', $current_time);
                    //日期不一致，则直接排除
                    if ($current_day != $cycle_start_day || $current_month != $cycle_start_month) break;
                    $difference = $current_year - $cycle_start_year;
                    $is_hold    = $difference / ($cycle_interval_factor + 1);
                    $is_hold    = is_int($is_hold);
                    //整除则为举办日
                    break;
            }

            return $is_hold;
        }
    }

    /**
     * 得到最近的一个活动开始时间
     * @param null|integer|string $time 当前日期时间或时间戳，默认为现在
     * @param bool                $next 是否强制查找下一个时间，若为false并且现在刚好活动开始则返回现在时间
     * @return false|string 最近的活动开始时间，若活动结束则返回false
     */
    public function getNextStartTime($time = null, $next = false)
    {
        $date                  = is_integer($time) ? date('Y-m-d', $time) : ($time ?: date('Y-m-d'));
        $time                  = strtotime($date);
        $cycle_start_date      = $this->getAttr('start_time');
        $cycle_start_time      = strtotime($cycle_start_date);
        $cycle_end_date        = $this->getAttr('end_time');
        $cycle_end_time        = strtotime($cycle_end_date);
        $cycle_type            = $this->getAttr('cycle_type');
        $cycle_interval_factor = $this->getAttr('cycle_interval');
        if ($cycle_start_time > $time) return $cycle_start_date;
        //活动已结束
        if ($cycle_end_time <= $time) return false;

        //如果活动已开始，则返回下一周期的开始时间
        $next_start_time = false;

        if ('week' === $cycle_type || 'day' === $cycle_type) {
            //周/天循环，天*间隔 为事件循环周期
            $cycle_interval = $this->getCycleInterval($cycle_type);
            $cycle_interval *= $cycle_interval_factor + 1;
            $count          = ($time - $cycle_start_time) / $cycle_interval;
            //刚好是活动开始时间
            if (is_integer($count) && !$next) return $date;
            //查找下一活动时间
            //整数周期
            $next_start_time = $cycle_start_time + ceil($count) * $cycle_interval;
        } else {
            //其他循环，则直接修改日期
            $cycle_start_year  = date('Y', $cycle_start_time);
            $cycle_start_month = date('m', $cycle_start_time);
            $cycle_start_day   = date('d', $cycle_start_time);
            $year              = date('Y', $time);
            $month             = date('m', $time);
            $day               = date('d', $time);
            $cycle_time        = date('H:i', $cycle_start_time);
            $year_difference   = $year - $cycle_start_year;
            $cycle_interval    = $cycle_interval_factor + 1;
            //TODO: 闰年
            switch ($cycle_type) {
                case 'month':
                    //按n月循环
                    //先转年差为月份
                    $month += 12 * $year_difference;
                    $month -= $cycle_start_month;
                    //计算下一周期月
                    $month /= $cycle_interval;
                    if (is_integer($month)) {
                        //若当前月为活动举办月
                        //且当前日为活动举办日，并且不强制返回下一日则返回今天
                        if (false === $next && $cycle_start_day === $day) return $date . ' ' . $cycle_time;
                        //若活动未开始则查找这一月
                        if ($cycle_start_day > $day) $month--;
                    }
                    //开始计算下一周期日
                    //取整得到周期月
                    $month = ceil($month) * $cycle_interval;
                    $month += $cycle_start_month;
                    $year  = $cycle_start_year + ceil($month / 12);
                    $month = $month % 12;

                    $next_start_time = sprintf('%s-%s-%s %s', $year, $month, $cycle_start_day, $cycle_time);
                    break;
                case 'year':
                    //按n年循环
                    $year            += $cycle_interval;
                    $next_start_time = sprintf('%s-%s-%s %s', $year, $month, $cycle_start_day, $cycle_time);
                    break;
            }
        }

        return $next_start_time;
    }

    /**
     * 得到周期
     * @param $cycle_type
     * @return float|int
     */
    public function getCycleInterval($cycle_type)
    {
        $interval   = 0;
        $start_time = strtotime($this->getAttr('start_time'));
        switch ($cycle_type) {
            case 'day':
                $interval = 86400;
                break;
            case 'week':
                $interval = 7 * 86400;
                break;
            case 'month':
                list($week_start, $week_end) = Datetime::monthFromTime($start_time);
                $interval = $week_end - $week_start;
                break;
            case 'year':
                list($week_start, $week_end) = Datetime::yearFromTime($start_time);
                $interval = $week_end - $week_start;
                break;
        }
        return $interval;
    }
}
