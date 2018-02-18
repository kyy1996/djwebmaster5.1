<?php

use think\migration\adapter\OptimizedMySqlAdapter;
use think\migration\Migrator;
use think\migration\db\Column;

class CreateActivityTable extends Migrator
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
        $table = $this->table('activity', ['comment' => '活动表', 'signed' => false]);
        $table->addColumn('name', 'string', ['comment' => '活动名称'])
              ->addColumn('type', 'string', ['comment' => '活动类型', 'default' => '教学活动'])
              ->addColumn('tags', 'json', ['comment' => '活动Tag标签', 'default' => '[]'])
              ->addColumn('lecturers', 'json', ['comment' => '讲师名字数组列表，{name: 讲师名, uid: 0/讲师UID}', 'default' => '[{name: "待定", id: 0}]'])
              ->addColumn('locations', 'json', ['comment' => '活动地点数组列表，{name: 地点名, id: 0/教室ID}', 'default' => '[{name: "待定", id: 0}]'])
              ->addColumn('start_time', 'datetime', ['comment' => '活动开始时间'])
              ->addColumn('cycle_type', 'enum', ['values' => ['day', 'week', 'month', 'year'], 'comment' => '循环周期类型，day-每天/week-每周/month-每月/year-每年', 'default' => 'week', 'null' => true])
              ->addColumn('cycle_interval', 'integer', ['comment' => '循环周期大小，每隔i天/周/月/年循环一次', 'default' => 1, 'limit' => OptimizedMySqlAdapter::INT_TINY, 'signed' => false])
              ->addColumn('end_time', 'datetime', ['comment' => '活动结束时间', 'default' => null, 'null' => true])
              ->addColumn('capacity', 'integer', ['comment' => '课堂容量', 'default' => 50, 'limit' => OptimizedMySqlAdapter::INT_TINY])
              ->addColumn('token_amount', 'integer', ['comment' => '已选课人数', 'default' => 0, 'limit' => OptimizedMySqlAdapter::INT_TINY])
              ->addColumn('status', 'integer', ['comment' => '状态，0-正常/1-隐藏/2-暂停，相加得到状态值', 'default' => 0, 'limit' => OptimizedMySqlAdapter::INT_TINY])
              ->addColumn('article_id', 'integer', ['comment' => '活动介绍文章ID', 'null' => true, 'signed' => false, 'default' => null])
              ->addColumn('description', 'text', ['comment' => '活动描述', 'limit' => OptimizedMySqlAdapter::TEXT_REGULAR, 'null' => true])
              ->addColumn('extra', 'json', ['comment' => '额外数据', 'default' => '{}'])
              ->addSoftDelete()
              ->addTimestamps()
              ->addForeignKey('article_id', 'article', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
              ->create();
    }
}
