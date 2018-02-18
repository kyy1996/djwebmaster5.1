<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 17/02/2018
 * Time: 12:40 PM
 */

namespace alen\helper;


use think\helper\Time;

class Datetime extends Time
{
    /**
     * 返回指定时间这一周开始和结束的时间戳
     * @param null|int $timestamp timestamp
     * @return array
     */
    public static function weekFromTime($timestamp = null)
    {
        $timestamp = $timestamp ?? time();
        return [
            strtotime(date('Y-m-d', strtotime("+0 week Monday", $timestamp))),
            strtotime(date('Y-m-d', strtotime("+0 week Sunday", $timestamp))) + 24 * 3600 - 1
        ];
    }

    /**
     * 返回指定时间这一月开始和结束的时间戳
     * @param null|int $timestamp
     * @return array
     */
    public static function monthFromTime($timestamp = null)
    {
        $timestamp = $timestamp ?: \time();
        return [
            mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)),
            mktime(23, 59, 59, date('m', $timestamp), date('t'), date('Y', $timestamp))
        ];
    }

    /**
     * 返回指定时间这一年开始和结束的时间戳
     * @param null|int $timestamp
     * @return array
     */
    public static function yearFromTime($timeStamp = null)
    {
        $timestamp = $timestamp ?: \time();
        return [
            mktime(0, 0, 0, 1, 1, date('Y', $timestamp)),
            mktime(23, 59, 59, 12, 31, date('Y', $timestamp))
        ];
    }

    /**
     * 返回指定时间这一天开始和结束的时间戳
     * @param null|int $timestamp
     * @return array
     */
    public static function dayFromTime($timeStamp = null)
    {
        $timestamp = $timestamp ?: \time();
        $start     = strtotime(date('Y-m-d', $timestamp));
        $end       = strtotime(date('Y-m-d', $timestamp + 86400)) - 1;
        return [
            $start, $end
        ];
    }
}