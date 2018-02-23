<?php

namespace app\common\behavior;

use app\common\authentication\AuthenticationManager;
use think\Request;

class RegisterAuthentication
{
    public function run(Request $request, $params)
    {
        bind('authentication', AuthenticationManager::class);
    }
}
