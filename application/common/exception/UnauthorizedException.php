<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 11/02/2018
 * Time: 8:18 PM
 */

namespace app\common\exception;

use think\Exception;

class UnauthorizedException extends Exception
{
    //已登录用户
    protected $user = null;

    public function __construct($message = "", $user = null, $code = 401)
    {
        $this->user = $user;
        parent::__construct($message, $code);
    }
}