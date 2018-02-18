<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateUserProfileTable extends Migrator
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
        $table = $this->table('user_profile', ['signed' => false, 'comment' => '用户个人资料表']);
        $table
            ->addColumn('uid', 'integer', ['signed' => false])
            ->addColumn('name', 'string', ['default' => '', 'comment' => '姓名'])
            ->addColumn('student_no', 'string', ['limit' => 15, 'default' => '', 'comment' => '学生学号'])
            ->addColumn('school', 'string', ['default' => '', 'comment' => '学院'])
            ->addColumn('class', 'string', ['default' => '', 'comment' => '班级'])
            ->addSoftDelete()
            ->addTimestamps()
            ->addIndex(['student_no'])
            ->addIndex(['uid'], ['unique' => true])
            ->addForeignKey('uid', 'user', 'uid', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->create();
    }
}
