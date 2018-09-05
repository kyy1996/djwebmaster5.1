<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 默认输出类型
    'default_return_type'   => 'json',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'   => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler' => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'     => 'callback',
    // 是否开启多语言
    'lang_switch_on'        => true,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'        => '',
    // 默认语言
    'default_lang'          => 'zh-cn',
    'exception_handle'      => \app\common\exception\ExceptionHandler::class,
];
