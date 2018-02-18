<?php


use think\migration\adapter\OptimizedMySqlAdapter;
use think\migration\Migrator;

class CreateAttachmentTable extends Migrator
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
        $table = $this->table('attachment', ['signed' => false, 'comment' => '附件（文件）表']);
        $table->addColumn('filename', 'string', ['comment' => '文件名'])
              ->addColumn('url', 'string', ['comment' => '访问URL'])
              ->addColumn('path', 'string', ['comment' => '存储路径'])
              ->addColumn('mime', 'string', ['comment' => '文件MIME类型', 'default' => 'application/octet- stream'])
              ->addColumn('md5', 'string', ['comment' => '文件MD5', 'limit' => 64, 'default' => ''])
              ->addColumn('uuid', 'string', ['comment' => '文件UUID', 'limit' => 36, 'default' => ''])
              ->addColumn('type', 'enum', ['comment' => '文件类型', 'default' => 'other', 'values' => ['image', 'audio', 'video', 'document', 'archive', 'other']])
              ->addColumn('size', 'integer', ['comment' => '文件大小，单位字节', 'signed' => false, 'limit' => OptimizedMySqlAdapter::INT_REGULAR, 'default' => 0])
              ->addColumn('user_id', 'integer', ['comment' => '上传用户ID', 'signed' => false, 'default' => null, 'null' => true])
              ->addColumn('description', 'text', ['comment' => '文件描述', 'null' => true, 'default' => null])
              ->addSoftDelete()
              ->addTimestamps()
              ->addForeignKey('user_id', 'user', 'uid', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
              ->addIndex("uuid", ['unique' => true])
              ->addIndex("url", ['unique' => true])
              ->addIndex("path")
              ->create();
    }
}
