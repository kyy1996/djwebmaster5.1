<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 21/02/2018
 * Time: 4:26 PM
 */

namespace app\common\authentication\guards;


use app\common\authentication\AuthenticateUser;
use app\common\authentication\guards\drivers\GuardDriver;
use think\Request;

class BaseGuard implements GuardInterface
{
    /** @var GuardDriver|null $driver 门卫驱动 */
    protected $driver = null;

    /** @var Request 请求对象 */
    protected $request;

    /** @var AuthenticateUser 用户模型 */
    protected $user;

    public function __construct(Request $request, string $driver = null)
    {
        $this->request = $request;
        if ($driver && class_exists($driver)) $this->driver = new $driver;
    }

    public function user(): AuthenticateUser
    {
        return $this->user ?: $this->getUserFromRequest();
    }

    public function setUser(AuthenticateUser $user)
    {
        return $this->user = $user;
    }

    public function id(): int
    {
        return $this->user() ? $this->user()->getUserIdentifier() : null;
    }

    public function guest(): bool
    {
        return !$this->user();
    }

    public function validate(array $credential): bool
    {
        $user = $this->getUserByCredential($credential);
        return $user && $this->driver->verifyUserCredential($credential, $user) ? !!$this->setUser($user) : false;
    }

    public function getUserByCredential(array $credential): AuthenticateUser
    {
        return $this->driver->getUserByCredential($credential);
    }

    /**
     * @param Request $request
     * @return BaseGuard
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function getUserFromRequest()
    {
        $uid = $this->request->session('uid');
        $uid && $this->user = $this->driver->getUserByCredential(['uid' => $uid]);
        return $this->user;
    }

    public function saveUserToRequest()
    {
        $uid = $this->user ? $this->user->getUserIdentifier() : null;
        $uid && $this->request->session('uid', $uid);
        return $uid;
    }

    protected function filterCredential(array $credential)
    {
        return array_intersect_key($credential, array_flip($this->credentialFields()));
    }

    protected function credentialFields()
    {
        return [$this->usernameField(), $this->passwordField()];
    }

    protected function passwordField()
    {
        return $this->driver->passwordField();
    }

    protected function usernameField()
    {
        return $this->driver->usernameField();
    }

    public function registerUser(array $credential)
    {
        return $this->driver->registerUser($credential);
    }
}