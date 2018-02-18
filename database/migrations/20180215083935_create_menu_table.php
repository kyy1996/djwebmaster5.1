<?php


use think\migration\Migrator;
use think\migration\db\Column;

class CreateMenuTable extends Migrator
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
        $table = $this->table('menu', ['signed' => false])->setComment('导航菜单与权限节点表');
        $table->addColumn(Column::string('module')->setComment('规则所属模块')->setDefault('admin'));
        $table->addColumn(Column::string('title')->setComment('标题'));
        $table->addColumn(Column::unsignedInteger('pid')->setComment('上级菜单ID')->setNUllable());
        $table->addColumn(Column::enum('type', ['url', 'menu'])
                                ->setComment('菜单类型，url-URL网址/menu-系统内地址')
                                ->setDefault('menu'));
        $table->addColumn(Column::tinyInteger('sort')->setDefault(0)->setComment('排序，数字越小越靠前')->setDefault(0));
        $table->addColumn(Column::string('uri')->setComment('目标地址'));
        $table->addColumn(Column::tinyInteger('hide')->setLimit(1)->setComment('是否隐藏')->setDefault(0));
        $table->addColumn(Column::text('description')->setComment('菜单描述')->setDefault(null)->setNullable());
        $table->addColumn(Column::string('group')->setComment('菜单分组')->setDefault(''));
        $table->addColumn(Column::string('icon_class')->setComment('菜单图标class名')->setDefault('fa-cogs'));
        $table->addColumn(Column::tinyInteger('is_dev')->setLimit(1)->setComment('是否仅开发者可见')->setDefault(0));
        $table->addColumn(Column::tinyInteger('status')->setLimit(1)->setComment('菜单与权限节点状态，1-正常/0-禁用')->setDefault(1));
        $table->addColumn(Column::string('condition')->setComment('规则附加条件，1-正常/0-禁用')->setDefault(''));
        $table->addSoftDelete();
        $table->addTimestamps();
        $table->addForeignKey('pid', 'menu', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE']);
        $table->create();
    }
}
