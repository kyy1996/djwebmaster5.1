<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 19/02/2018
 * Time: 8:27 PM
 */

namespace ExtraModel;

use think\Loader;
use think\model\relation\HasManyThrough;

class HasManyThroughBelong extends HasManyThrough
{
    /** @var \think\Model $parent 父模型，也就是查询发起模型 */
    protected $parent;
    /** @var \think\Model $through 中间表模型 */
    protected $through;

    /**
     * 执行基础查询（仅执行一次）
     * @access protected
     * @return void
     */
    protected function baseQuery()
    {
        if (empty($this->baseQuery) && $this->parent->getData()) {
            $through      = $this->through; //中间表模型
            $alias        = Loader::parseName(basename(str_replace('\\', '/', $this->model)));//目标表表名别名
            $throughTable = $through->getTable();   //中间表表名
            $pk           = $this->getPk();    //目标表主键
            $throughKey   = $this->throughKey;  //目标表在中间表的外键
            $modelTable   = $this->parent->getTable();  //查询发起表表名
            $fields       = $this->getQueryFields($alias);  //查询字段

            $this->query
                ->field($fields)
                ->alias($alias)
                ->join($throughTable, $throughTable . '.' . $throughKey . '=' . $alias . '.' . $pk)
                ->join($modelTable, $modelTable . '.' . $this->localKey . '=' . $throughTable . '.' . $this->foreignKey)
                ->where($throughTable . '.' . $this->foreignKey, $this->parent->{$this->localKey})
                ->group($throughTable . '.' . $throughKey);

            $this->baseQuery = true;
        }
    }
}