<?php

use think\migration\adapter\OptimizedMySqlAdapter;
use think\migration\Migrator;
use think\migration\db\Column;

class CreateJobTable extends Migrator
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
        $table = $this->table('job', ['signed' => false])->setComment('管理层招募信息表');
        $table->addColumn(Column::string('name')->setComment('职位名称'));
        $table->addColumn(Column::string('department')->setComment('职位所属部门'));
        $table->addColumn(Column::text('description')
                                ->setComment('职位描述')
                                ->setLimit(OptimizedMySqlAdapter::TEXT_REGULAR)
                                ->setNullable());
        $table->addColumn(Column::text('requirement')
                                ->setComment('职位要求')
                                ->setLimit(OptimizedMySqlAdapter::TEXT_REGULAR)
                                ->setNullable());
        $table->addColumn(Column::unsignedInteger('article_id')->setComment('相关文章ID')->setNullable());
        $table->addColumn(Column::unsignedInteger('user_id')->setComment('发布者用户ID')->setNullable());
        $table->addIndex(['name', 'department']);
        $table->addForeignKey('article_id', 'article', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE']);
        $table->addForeignKey('user_id', 'user', 'uid', ['update' => 'CASCADE', 'delete' => 'CASCADE']);
        $table->addSoftDelete()->addTimestamps();
        $table->create();
    }
}
