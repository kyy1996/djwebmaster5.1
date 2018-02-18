<?php


use think\migration\Migrator;
use think\migration\db\Column;

class CreateUserGroupTable extends Migrator
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
        $table = $this->table('user_group', ['signed' => false])->setComment('用户组表');
        $table->addColumn(Column::string('module')->setDefault('')->setComment('用户组所属模块'));
        $table->addColumn(Column::string('name')->setComment('用户组名称'));
        $table->addColumn(Column::string('description')->setNullable()->setComment('用户组描述'));
        $table->addColumn(Column::tinyInteger('status')->setLimit(1)->setDefault(1)->setComment('用户组状态：为1正常，为0禁用'));
        $table->addColumn(Column::string('rule')->setLimit(500)->setDefault('')->setComment('用户组拥有权限规则，来自menu表'));
        $table->addSoftDelete()->addTimestamps();
        $table->create();
    }
}
