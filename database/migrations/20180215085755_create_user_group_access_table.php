<?php


use think\migration\Migrator;
use think\migration\db\Column;

class CreateUserGroupAccessTable extends Migrator
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
        $table = $this->table('user_group_access')->setId(false)->setComment('用户与用户组分配表');
        $table->addColumn(Column::unsignedInteger('uid')->setComment('用户ID'));
        $table->addColumn(Column::unsignedInteger('user_group_id')->setComment('用户组ID'));
        $table->addForeignKey('uid', 'user', 'uid', ['update' => 'CASCADE', 'delete' => 'CASCADE']);
        $table->addForeignKey('user_group_id', 'user_group', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE']);
        $table->addIndex(['uid', 'user_group_id'], ['unique' => true]);
        $table->create();
    }
}
