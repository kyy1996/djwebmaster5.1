<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class UserProfile extends Model
{
    use SoftDelete;
}
