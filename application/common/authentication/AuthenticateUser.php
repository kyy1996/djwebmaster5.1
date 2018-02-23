<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 21/02/2018
 * Time: 6:56 PM
 */

namespace app\common\authentication;

/**
 * Trait AuthenticateUser
 * 用户鉴定
 * @see     \think\Model 模型文件
 * @method getPk()  得到模型主键
 * @package app\common\authentication
 */
trait AuthenticateUser
{
    /**
     * 得到用户UID字段名称，默认为主键
     * @return string
     */
    public function getUserIdentifierKey()
    {
        return $this->getPk();
    }

    /**
     * 得到用户UID
     * @return string
     */
    public function getUserIdentifier()
    {
        return $this->getAttr($this->getUserIdentifierKey());
    }

    /**
     * 得到加密后的密码
     * @return string
     */
    public function getHashedPassword()
    {
        return $this->getAttr($this->getHashedPasswordKey());
    }

    /**
     * 得到加密密码字段名称
     * @return string
     */
    public function getHashedPasswordKey()
    {
        return 'password_hashed';
    }

    /**
     * 得到密码加密盐值
     * @return string
     */
    public function getPasswordSalt()
    {
        return $this->getAttr($this->getPasswordSaltKey());
    }

    public function getPasswordSaltKey()
    {
        return 'password_salt';
    }
}