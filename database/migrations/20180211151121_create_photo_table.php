<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreatePhotoTable extends Migrator
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
        $table = $this->table('photo', ['signed' => false])->setComment('相册表，配合附件表一起使用');
        $table->addColumn(Column::string('title')->setComment('图片标题')->setDefault(''));
        $table->addColumn(Column::string('category')->setComment('分类标题')->setDefault(''));
        $table->addColumn(Column::unsignedInteger('attachment_id')->setComment('文件ID'));
        $table->addColumn(Column::unsignedInteger('thumbnail_id')
                                ->setComment('缩略图ID')
                                ->setDefault(null)
                                ->setNullable());
        $table->addColumn(Column::string('ip')->setLimit(40)->setComment('上传者IP，支持IPv6')->setDefault('0.0.0.0'));
        $table->addColumn(Column::unsignedInteger('user_id')->setComment('上传者用户ID')->setDefault(null)->setNullable());
        $table->addColumn(Column::boolean('hide')->setComment('是否隐藏')->setDefault(false));
        $table->addSoftDelete()->addTimestamps();
        $table->addForeignKey('attachment_id', 'attachment', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE']);
        $table->addForeignKey('user_id', 'user', 'uid', ['update' => 'CASCADE', 'delete' => 'CASCADE']);
        $table->create();
    }
}
