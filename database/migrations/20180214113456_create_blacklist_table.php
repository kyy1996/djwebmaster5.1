<?php


use think\migration\adapter\OptimizedMySqlAdapter;
use think\migration\Migrator;
use think\migration\db\Column;

class CreateBlacklistTable extends Migrator
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
        $table = $this->table('blacklist', ['signed' => false, 'comment' => '黑名单表']);
        $table
            ->addColumn('user_id', 'integer', ['comment' => '黑名单用户ID', 'signed' => false, 'null' => true])
            ->addColumn('student_no', 'string', ['limit' => 15, 'default' => '', 'comment' => '学生学号'])
            ->addColumn('school', 'string', ['default' => '', 'comment' => '学院'])
            ->addColumn('class', 'string', ['default' => '', 'comment' => '班级'])
            ->addColumn('name', 'string', ['default' => '', 'comment' => '姓名'])
            ->addColumn('reason', 'text', ['comment' => '理由', 'limit' => OptimizedMySqlAdapter::TEXT_REGULAR, 'null' => true, 'default' => null])
            ->addColumn('operator_ip', 'string', ['comment' => '操作人IP', 'limit' => 40])
            ->addColumn('operator_user_id', 'integer', ['comment' => '操作人IP', 'limit' => 40, 'signed' => false])
            ->addSoftDelete()
            ->addTimestamps()
            ->addIndex(['student_no', 'school', 'class', 'name'], ['unique' => true])
            ->addIndex(['user_id'], ['unique' => true])
            ->addForeignKey('user_id', 'user', 'uid', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addForeignKey('operator_user_id', 'user', 'uid', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->create();
    }
}
