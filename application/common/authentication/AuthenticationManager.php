<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 22/02/2018
 * Time: 3:30 PM
 */

namespace app\common\authentication;


use app\common\authentication\guards\BaseGuard;
use app\common\authentication\guards\drivers\UserModelDriver;
use app\common\authentication\guards\GuardInterface;
use app\common\authentication\guards\TokenGuard;
use think\Request;

/**
 * Class AuthenticationManager
 * @see     GuardInterface
 * @package app\common\authentication
 */
class AuthenticationManager
{
    protected $defaultGuardsList = [
        BaseGuard::class,
        TokenGuard::class,
    ];

    /** @var GuardInterface[] 门卫队列 */
    protected $guards = [];

    /** @var GuardInterface 默认门卫 */
    protected $defaultGuard;

    /** @var string 默认门卫驱动 */
    protected $defaultGuardDriver = UserModelDriver::class;

    /** @var Request */
    protected $request;

    public function __construct(Request $request, $guard = null)
    {
        $this->registerDefaultGuards();
        $this->guard($guard);
        $this->setRequest($request);
    }

    /**
     * 注册默认门卫队列
     */
    protected function registerDefaultGuards()
    {
        foreach ($this->defaultGuardsList as $guard) {
            if (class_exists($guard))
                $this->guards[$guard] = new $guard($this->request, $this->defaultGuardDriver);
        }
    }

    /**
     * 设置默认门卫并返回门卫实例
     * @param null $guard
     * @return GuardInterface
     */
    public function guard($guard = null)
    {
        if ($guard !== null) {
            if (key_exists($guard, $this->guards)) {
                $this->defaultGuard = $this->guards[$guard];
                return $this->defaultGuard;
            }
            throw new \InvalidArgumentException(lang("Guard {:guard_name} 不存在", ['guard_name' => $guard]));
        }

        $this->defaultGuard = array_values($this->guards)[0];

        //取第一个读取到用户的门卫为默认门卫
        foreach ($this->guards as $guard) {
            if ($guard->user()) {
                $this->defaultGuard = $guard;
                break;
            }
        }
        return $this->defaultGuard;
    }

    public function setRequest(Request $request)
    {
        return $this->request = $request;
    }

    /**
     * 注册新的门卫
     * @param $guard
     * @return mixed
     */
    public function registerGuard($guard)
    {
        if (!class_exists($guard))
            throw new \InvalidArgumentException(lang('Guard {:guard_name} 不存在', ['guard_name' => $guard]));
        if (key_exists($guard, $this->guards))
            throw new \InvalidArgumentException(lang('Guard {:guard_name} 已存在', ['guard_name' => $guard]));
        return $this->guards[$guard] = new $guard($this->request, $this->defaultGuardDriver);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->defaultGuard, $name)) {
            return call_user_func_array([$this->defaultGuard, $name], $arguments);
        } else {
            throw new \LogicException(lang('Method %s does not exists', [$name]));
        }
    }
}