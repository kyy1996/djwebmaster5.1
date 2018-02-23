<?php

namespace app\common\model;

use think\Model;
use think\model\Collection;

class CommonConfig extends Model
{
    protected        $pk         = 'name';
    protected static $properties = null;

    /**
     * 初始化处理
     * @access protected
     * @return void
     * @throws \think\exception\DbException
     */
    protected static function init()
    {
        if (static::$properties !== null) return;
        /** @var Collection $property */
        $property           = static::all();
        static::$properties = $property->column('value', 'name');
    }

    /**
     * 得到属性
     * @param string|null            $name
     * @param string|\stdClass|array $default
     * @return string|\stdClass|array
     * @throws \think\exception\DbException
     */
    public static function getProperty(string $name = null, $default = null)
    {
        if (static::$properties === null) static::init();
        if ($name === null) return static::$properties;
        $value = static::$properties[$name] ?? $default;
        $value = json_decode($value) ?: $value;
        return $value;
    }

    /**
     * 设置属性
     * @param string                 $name
     * @param string|\stdClass|array $value
     * @return integer
     * @throws \think\exception\DbException
     */
    public static function setProperty(string $name, $value)
    {
        if (static::$properties === null) static::init();
        $value                     = json_encode($value) ?: $value;
        static::$properties[$name] = $value;
        return (new static)->insert(['name' => $name, 'value' => $value], true);
    }
}
