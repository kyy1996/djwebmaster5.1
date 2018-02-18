<?php


use think\migration\Migrator;
use think\migration\db\Column;

class CreateCategoryTable extends Migrator
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
        $table = $this->table('category', ['signed' => false, 'comment' => '多级分类表']);
        $table->addColumn('name', 'string', ['comment' => '分类名称'])
              ->addColumn('pid', 'integer', ['signed' => false, 'comment' => '上级分类ID', 'null' => true, 'default' => null])
              ->addSoftDelete()
              ->addTimestamps()
              ->addForeignKey('pid', 'category', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
              ->create();
    }
}
