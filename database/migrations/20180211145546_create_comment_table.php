<?php

use think\migration\adapter\OptimizedMySqlAdapter;
use think\migration\Migrator;
use think\migration\db\Column;

class CreateCommentTable extends Migrator
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
        $table = $this->table('comment', ['comment' => '评论表', 'signed' => false]);
        $table->addColumn('title', 'string', ['comment' => '评论标题', 'default' => ''])
              ->addColumn('pid', 'integer', ['comment' => '上级评论ID，也就是被回复的ID', 'signed' => false, 'null' => true])
              ->addColumn('content', 'text', ['comment' => '评论内容', 'limit' => OptimizedMySqlAdapter::TEXT_REGULAR])
              ->addColumn('user_id', 'integer', ['comment' => '发表人用户UID', 'null' => true, 'signed' => false])
              ->addColumn('nickname', 'string', ['comment' => '发表人昵称'])
              ->addColumn('email', 'string', ['comment' => '发表人Email', 'default' => ''])
              ->addColumn('mobile', 'string', ['comment' => '发表人手机号', 'default' => ''])
              ->addColumn('contact', 'string', ['comment' => '发表人QQ', 'default' => ''])
              ->addColumn('attachments', 'json', ['comment' => '评论附件json对象', 'default' => '[]'])
              ->addColumn('extra', 'json', ['comment' => '评论附加属性', 'default' => '{}'])
              ->addColumn('publish_ip', 'string', ['default' => '0.0.0.0', 'limit' => 40, 'comment' => '发布者IP，支持ipv6'])
              ->addColumn('update_ip', 'string', ['default' => null, 'null' => true, 'limit' => 40, 'comment' => '修改者IP，支持ipv6'])
              ->addNullableMorphs('commentable')
              ->addSoftDelete()
              ->addTimestamps()
              ->addForeignKey('user_id', 'user', 'uid', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
              ->addForeignKey('pid', 'comment', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
              ->create();
    }
}
