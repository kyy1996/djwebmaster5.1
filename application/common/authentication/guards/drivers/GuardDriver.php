<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 21/02/2018
 * Time: 4:27 PM
 */

namespace app\common\authentication\guards\drivers;


use app\common\authentication\AuthenticateUser;

abstract class GuardDriver
{
    /** @var boolean $hashNeeded 是否需要加密密码 */
    protected $hashNeeded = false;

    abstract public function getUserByCredential(array $credential): AuthenticateUser;

    abstract public function verifyUserCredential(array $credential, AuthenticateUser $user);

    /**
     * 注册用户
     * @param array $credential
     * @return mixed
     */
    abstract public function registerUser(array $credential);

    /**
     * 加密密码
     * @param string $password
     * @return string
     */
    public function hashPassword(string $password)
    {
        return $password;
    }

    /**
     * 验证密码是否正确
     * @param string $origin_password 数据库中已加密密码
     * @param string $raw_password    提交验证的密码原文
     * @return bool
     */
    public function verifyPassword(string $origin_password, string $raw_password)
    {
        return $this->hashPassword($raw_password) === $origin_password;
    }

    /**
     * 是否需要加密
     * @return bool
     */
    public function isHashNeeded(): bool
    {
        return $this->hashNeeded;
    }

    /**
     * 密码字段
     * @return string
     */
    public function passwordField(): string
    {
        return 'password';
    }

    /**
     * 登录用户米字段
     * @return string
     */
    public function usernameField(): string
    {
        return 'username';
    }
}