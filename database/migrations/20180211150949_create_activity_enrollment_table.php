<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateActivityEnrollmentTable extends Migrator
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
        $table = $this->table('activity_enrollment', ['comment' => '活动报名表', 'signed' => false]);
        $table->addColumn(Column::unsignedInteger('activity_id')->setComment('参加活动ID'))
              ->addColumn(Column::unsignedInteger('user_id')->setComment('报名用户ID')->setNullable())
              ->addColumn(Column::string('student_no')->setLimit(15)->setComment('学生学号'))
              ->addColumn(Column::string('class')->setComment('班级'))
              ->addColumn(Column::string('name')->setComment('学生姓名'))
              ->addColumn(Column::string('contact')->setComment('报名人QQ')->setDefault(''))
              ->addColumn(Column::tinyInteger('valid')->setLimit(1)->setDefault(1)->setComment('是否有效'))
              ->addColumn(Column::string('ip')->setLimit(40)->setComment('报名人IP，支持IPv6')->setDefault('0.0.0.0'))
              ->addColumn(Column::string('ua')->setComment('报名人User-Agent')->setDefault(''))
              ->addSoftDelete()->addTimestamps()
              ->addIndex(['student_no', 'activity_id'])
              ->addIndex(['user_id', 'activity_id'])
              ->addForeignKey('activity_id', 'activity', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
              ->addForeignKey('user_id', 'user', 'uid', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
              ->create();
    }
}