<?php

namespace app\common\model;

use think\Loader;
use think\Model;
use think\model\concern\SoftDelete;

class Menu extends Model
{
    use SoftDelete;

    protected $type = [
        'status' => 'boolean',
        'is_dev' => 'boolean',
        'hide'   => 'boolean'
    ];

    protected static $Tree  = [];
    protected static $Menus = [];


    /**
     * 初始化处理
     * @access protected
     * @return void
     */
    protected static function init()
    {
        $menu          = (new static)->order("sort", "DESC")->order("id", "ASC")->select();
        $menus         = $menu->toArray();
        static::$Menus = $menus;
        static::$Tree  = list2tree($menus);
    }

    public function getTree($all = true)
    {
        if ($all) return static::$Tree;
        static $visible_tree = [];
        if (!$visible_tree) {
            $visible_tree = list2tree($this->getMenu($all));
        }
        return $visible_tree;
    }

    public function getMenu($all = true)
    {
        if ($all) return static::$Menus;
        static $visible_menus = [];
        if (!$visible_menus) {
            foreach (static::$Menus as $menu) {
                if ($menu['status'] && !$menu['hide'])
                    $visible_menus[] = $menu;
            }
        }
        return $visible_menus;
    }

    public function getParents($id)
    {
        $items  = [];
        $item   = $this->getMenuItem($id);
        $parent = $item;
        do {
            $items[$parent['id']] = $parent;
        } while ($parent = $this->getMenuItem($parent['pid']));
        return array_reverse($items);
    }

    public function getMenuItem($value, $type = "id")
    {
        $current = false;
        foreach (static::$Menus as $menu) {
            if (strtolower($menu[$type]) == strtolower($value)) {
                $current = $menu;
            }
        }
        return $current;
    }

    public function getNavigationInfo()
    {
        $current         = $this->getMenuItem($this->getCurrentUri(), "url");
        $navigation_tree = $this->getTree(false);
        $navigation_menu = $this->getMenu(false);
        $breadcrumb      = $this->getParents($current['id']);
        $title           = [];
        foreach (array_reverse($breadcrumb) as $item) {
            $title[] = $item['title'];
        }
        $info = [
            'current'    => $current,
            'tree'       => $navigation_tree,
            'menu'       => $navigation_menu,
            'breadcrumb' => $breadcrumb,
            'title'      => $title
        ];
        return $info;
    }

    public function getCurrentUri()
    {
        $module     = request()::module();
        $controller = Loader::parseName(request()::controller(), 0);
        $action     = request()::action();
        return $module . "/" . $controller . "/" . $action;
    }

    protected function setStatusAttr()
    {
        if (request()::has("status")) return 1;
        else return 0;
    }

    protected function setHideAttr()
    {
        if (request()::has("hide")) return 1;
        else return 0;
    }
}
