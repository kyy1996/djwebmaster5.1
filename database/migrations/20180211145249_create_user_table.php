<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateUserTable extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('user', ['signed' => false, 'id' => 'uid', 'comment' => '用户基础信息表']);
        $table
            ->addColumn('email', 'string', ['comment' => 'Email地址'])
            ->addColumn('password', 'string', ['limit' => 64, 'comment' => '密码'])
            ->addColumn('password_salt', 'string', ['default' => rand(1000, 9999), 'comment' => '密码加密盐值'])
            ->addColumn('avatar', 'string', ['default' => '', 'comment' => '头像URL'])
            ->addColumn('mobile', 'string', ['comment' => '手机号'])
            ->addColumn('admin', 'boolean', ['default' => false, 'comment' => '用户是否是超级管理员，1-超级管理员/0-普通用户'])
            ->addColumn('status', 'boolean', ['default' => true, 'comment' => '用户状态，1-启用/0-停用'])
            ->addColumn('ip', 'string', ['default' => '0.0.0.0', 'limit' => 40, 'comment' => '注册IP，支持ipv6'])
            ->addSoftDelete()
            ->addTimestamps()
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['mobile'], ['unique' => true])
            ->create();
    }
}
