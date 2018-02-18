<?php

use think\migration\adapter\OptimizedMySqlAdapter;
use think\migration\Migrator;
use think\migration\db\Column;

class CreateArticleTable extends Migrator
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
        $table = $this->table('article', ['signed' => false, 'comment' => '文章表']);
        $table
            ->addColumn('title', 'string', ['comment' => '文章标题'])
            ->addColumn('pid', 'integer', ['comment' => '父级文章ID，本文是否是某篇文章的子章节', 'signed' => false, 'null' => true])
            ->addColumn('description', 'text', ['comment' => '文章短描述', 'null' => true, 'limit' => OptimizedMySqlAdapter::TEXT_REGULAR])
            ->addColumn('content', 'text', ['comment' => '文章内容', 'limit' => OptimizedMySqlAdapter::TEXT_LONG])
            ->addColumn('tag', 'json', ['comment' => '文章Tag标签', 'default' => '[]'])
            ->addColumn('category_id', 'integer', ['signed' => false, 'comment' => '分类id，0为未分类', 'default' => null, 'null' => true])
            ->addColumn('publish_user_id', 'integer', ['comment' => '发表者用户id，可为空', 'default' => null, 'signed' => false, 'null' => true])
            ->addColumn('update_user_id', 'integer', ['comment' => '修改者用户id，可为空', 'default' => null, 'signed' => false, 'null' => true])
            ->addColumn('hide', 'boolean', ['comment' => '是否隐藏', 'default' => false])
            ->addColumn('mode', 'string', ['comment' => '文章权限，1-阅读/2-修改，权限相加得到权限，顺序普通用户-游客', 'default' => '11', 'limit' => 2])
            ->addColumn('cover_img_id', 'integer', ['comment' => '封面图片，对应attachment附件表id', 'signed' => false, 'default' => null, 'null' => true])
            ->addColumn('attachments', 'json', ['comment' => '文章附件json对象', 'default' => '[]'])
            ->addColumn('read_count', 'integer', ['comment' => '文章已读人数', 'default' => 0, 'limit' => OptimizedMySqlAdapter::INT_TINY])
            ->addColumn('publish_ip', 'string', ['default' => '0.0.0.0', 'limit' => 40, 'comment' => '发布者IP，支持ipv6'])
            ->addColumn('update_ip', 'string', ['default' => null, 'null' => true, 'limit' => 40, 'comment' => '修改者IP，支持ipv6'])
            ->addColumn('extra', 'json', ['comment' => '额外属性', 'default' => '{}'])
            ->addSoftDelete()
            ->addTimestamps()
            ->addForeignKey('pid', 'article', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('category_id', 'category', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('publish_user_id', 'user', 'uid', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('update_user_id', 'user', 'uid', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('cover_img_id', 'attachment', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
