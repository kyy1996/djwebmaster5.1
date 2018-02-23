<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 22/02/2018
 * Time: 10:17 AM
 */

namespace app\common\authentication\guards;


use app\common\authentication\AuthenticateUser;

interface GuardInterface
{
    public function user(): AuthenticateUser;

    public function id(): int;

    public function guest(): bool;

    public function getUserByCredential(array $credential): AuthenticateUser;

    public function validate(array $credential): bool;

    public function setUser(AuthenticateUser $user);
}