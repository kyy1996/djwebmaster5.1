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
     * 得到属性，支持批量读取
     * @param string|null|array      $name
     * @param string|\stdClass|array $default
     * @return string|\stdClass|array
     * @throws \think\exception\DbException
     */
    public static function getProperty($name = null, $default = null)
    {
        if (static::$properties === null) static::init();
        if ($name === null) return static::$properties;
        if (is_string($name)) {
            $value = static::$properties[$name] ?? $default;
            $value = json_decode($value) ?: $value;
        } else {
            $value = [];
            foreach ($name as $index => $key) {
                $value[$key] = static::getProperty($key, is_array($default) ? $default[$index] : $default);
            }
        }
        return $value;
    }

    /**
     * 设置属性，支持批量设置
     * @param string|array           $name
     * @param string|\stdClass|array $value
     * @return integer
     * @throws \think\exception\DbException
     */
    public static function setProperty($name, $value = null)
    {
        if (static::$properties === null) static::init();
        if (is_string($name)) {
            $value                     = json_encode($value) ?: $value;
            static::$properties[$name] = $value;
            return (new static)->insert(['name' => $name, 'value' => $value], true);
        } else {
            $data = [];
            foreach ($name as $key => $value) {
                $data[] = ['name' => $key, 'value' => json_encode($value) ?: $value];
            }
            return (new static())->insertAll($data, true);
        }
    }
}
