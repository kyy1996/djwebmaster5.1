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
        if (static::$properties === null) {
            /** @var Collection $property */
            $property           = static::all();
            static::$properties = $property->column('value', 'name');
        }
    }

    public static function getProperty($name = null, $default = null)
    {
        if ($name === null) return static::$properties;
        $value = static::$properties[$name] ?? $default;
        return $value;
    }

    public static function setProperty($name, $value)
    {
        static::$properties[$name] = $value;
        return (new static)->insert(['name' => $name, 'value' => $value], true);
    }
}
