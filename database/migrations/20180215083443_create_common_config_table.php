<?php


use think\migration\Migrator;
use think\migration\db\Column;

class CreateCommonConfigTable extends Migrator
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
        $table = $this->table('common_config')->setPrimaryKey('name')->setId(false)->setComment('网站通用配置');
        $table->addColumn(Column::string('name')->setComment('配置项名称'));
        $table->addColumn(Column::string('type')->setComment('配置类型')->setDefault(''));
        $table->addColumn(Column::string('value')->setComment('配置项值'));
//        $table->addIndex('name', ['unique' => true]);
        $table->create();

    }
}
