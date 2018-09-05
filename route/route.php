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

//use think\facade\Route;
Route::get('/', function () {
});

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});


Route::group('v1', function () {
    //API路由
    Route::group('api', function () {
        Route::resource('users', 'api/users');
    });
});

return [

];
