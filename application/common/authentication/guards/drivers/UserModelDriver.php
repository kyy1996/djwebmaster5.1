<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 21/02/2018
 * Time: 4:25 PM
 */

namespace app\common\authentication\guards\drivers;


use app\common\authentication\AuthenticateUser;
use app\common\model\User;

class UserModelDriver extends GuardDriver
{
    /** @var AuthenticateUser|\think\Model $model */
    protected $model;

    public function __construct(AuthenticateUser $model = null)
    {
        $this->model = $model ?? new User();
    }

    /**
     * 根据请求得到用户模型
     * @param array $credential
     * @return AuthenticateUser
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserByCredential(array $credential): AuthenticateUser
    {
        $model = $this->model;
        foreach ($credential as $key => $value) {
            $model->where($key, $value);
        }
        return $model->find();
    }

    public function verifyUserCredential(array $credential, AuthenticateUser $user)
    {
        $origin_password = $user->getHashedPassword();
        $raw_password    = $credential[$this->passwordField()];
        $this->verifyPassword($origin_password, $raw_password);
    }

    public function verifyPassword(string $raw_password, string $hash)
    {
        return password_verify($raw_password, $hash);
    }

    public function hashPassword(string $password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 注册用户
     * @param array $credential
     * @return AuthenticateUser|\think\Model
     */
    public function registerUser(array $credential)
    {
        $this->model = $this->model->create($credential);
        return $this->model;
    }
}