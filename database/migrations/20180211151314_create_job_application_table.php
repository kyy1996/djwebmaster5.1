<?php

use think\migration\adapter\OptimizedMySqlAdapter;
use think\migration\Migrator;
use think\migration\db\Column;

class CreateJobApplicationTable extends Migrator
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
        $table = $this->table('job_application', ['comment' => '职位报名表', 'signed' => false]);
        $table->addColumn(Column::unsignedInteger('job_id')->setComment('申请职位ID'))
              ->addColumn(Column::unsignedInteger('user_id')->setComment('报名用户ID')->setNullable())
              ->addColumn(Column::string('student_no')->setLimit(15)->setComment('学生学号'))
              ->addColumn(Column::string('class')->setComment('班级'))
              ->addColumn(Column::string('name')->setComment('学生姓名'))
              ->addColumn(Column::string('contact')->setComment('报名人QQ')->setDefault(''))
              ->addColumn(Column::text('resume')->setComment('申请简历')->setLimit(OptimizedMySqlAdapter::TEXT_REGULAR))
              ->addColumn(Column::tinyInteger('status')
                                ->setLimit(1)
                                ->setDefault(0)
                                ->setComment('当前状态，0-已申请/1-已通过/-1-已拒绝'))
              ->addColumn(Column::string('ip')
                                ->setLimit(40)
                                ->setComment('报名人IP，支持IPv6')
                                ->setDefault('0.0.0.0'))
              ->addColumn(Column::text('reason')
                                ->setNullable()
                                ->setComment('操作理由')
                                ->setLimit(OptimizedMySqlAdapter::TEXT_REGULAR))
              ->addColumn(Column::unsignedInteger('operator_user_id')->setNullable()->setComment('操作人用户uid'))
              ->addColumn(Column::string('ua')->setComment('报名人User-Agent')->setDefault(''))
              ->addSoftDelete()->addTimestamps()
              ->addIndex(['student_no', 'job_id'])
              ->addIndex(['user_id', 'job_id'])
              ->addForeignKey('job_id', 'job', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
              ->addForeignKey('user_id', 'user', 'uid', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
              ->addForeignKey('operator_user_id', 'user', 'uid', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
              ->create();
    }
}
