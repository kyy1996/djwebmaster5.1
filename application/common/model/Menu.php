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

    protected $auto = ['status', 'hide', 'is_dev'];

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

    public function getTree($all = true, $depth = true)
    {
        static $visible_tree = [];
        if ($all) return static::$Tree;
        if (!$visible_tree) {
            $visible_tree = list2tree($this->getMenu($all));
            is_integer($depth) && $visible_tree = tree2tree($visible_tree, $depth);
        }
        return $visible_tree;
    }

    public function getMenu($all = true)
    {
        static $visible_menus = null;
        if ($all) return static::$Menus;
        if ($visible_menus === null) {
            $visible_menus = [];
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
        do
            array_unshift($items, $parent);
        while ($parent = $this->getMenuItem($parent['pid']));
        return $items;
    }

    public function getMenuItem($value, $type = "id", $last = false)
    {
        $current = false;
        foreach (static::$Menus as $menu) {
            if (strtolower($menu[$type]) === strtolower($value)) {
                $current = $menu;
                if (!$last) break;
            }
        }
        return $current;
    }

    public function getNavigationInfo()
    {
        $current         = $this->getMenuItem($this->getCurrentUri(), 'uri');
        $navigation_tree = $this->getTree(false);
        $navigation_menu = $this->getMenu(false);
        $breadcrumb      = $this->getParents($current['id']);
        $title           = [];
        foreach ($breadcrumb as $item) {
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
        return $module . '/' . $controller . '/' . $action;
    }

    public function setStatusAttr($status = null)
    {
        return !!$status;
    }

    public function setHideAttr($hide = null)
    {
        return !!$hide;
    }

    public function setIsDevAttr($is_dev = null)
    {
        return !!$is_dev;
    }
}
