<?php

namespace app\api\controller;

use think\Controller as BaseController;

class Controller extends BaseController
{
    protected function success($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        parent::success($msg, $url ?? '', $data, $wait, $header);
    }

    protected function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        parent::error($msg, $url ?? '', $data, $wait, $header);
    }
}
