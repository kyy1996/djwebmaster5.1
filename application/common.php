<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


if (!function_exists("list2tree")) {
    /**
     * 数据集转树数组
     * @param array|ArrayAccess $list
     * @param string            $id
     * @param string            $pid
     * @param string            $child
     * @param int               $root
     * @return array
     */
    function list2tree($list, $id = "id", $pid = "pid", $child = "_child", $root = 0)
    {
        ($list instanceof \think\Collection) && $list = $list->toArray();
        ($list instanceof \think\Paginator) && $list = $list->getCollection()->toArray();
        $refer = [];
        //转换为id数组
        foreach ($list as $key => $item) {
            $refer[$item[$id]] = &$list[$key];
        }
        $tree = [];
        //构造树
        foreach ($list as $key => $item) {
            if ($item[$pid] == $root) {
                //根节点
                $tree[$item[$id]] = &$list[$key];
            } else {
                if (key_exists($item[$pid], $refer)) {
                    $refer[$item[$pid]][$child][] = &$list[$key];
                }
            }
        }

        return $tree;
    }
}

if (!function_exists('tree2list')) {
    /**
     * 树数组转有序列表，可标识层级数
     * @param array  $tree    树数组
     * @param string $child   子项目索引
     * @param string $level   层级数
     * @param int    $current 递归层级树
     * @return array
     */
    function tree2list($tree, $child = "_child", $level = "_level", $current = 0)
    {
        if (!is_array($tree)) return [];
        $list = [];
        foreach ($tree as $item) {
            $base_count = $current;
            $level && $item[$level] = $base_count;
            $child_items = [];
            if (key_exists($child, $item)) $child_items = $item[$child];
            $item[$child] = [];

            $list[] = $item;
            $list   = array_merge($list, tree2list($child_items, $child, $level, $base_count + 1));
        }

        return $list;
    }
}

if (!function_exists('tree2tree')) {
    /**
     * 树数组转指定层级树
     * @param array  $tree  树数组
     * @param int    $depth 最大深度
     * @param string $child 子项索引
     * @return array
     * @internal param int $current 当前层级
     */
    function tree2tree($tree, $depth = 1, $child = "_child")
    {
        $result = &$tree;
        if ($depth <= 0) $result = tree2list($result, $child, '');
        else
            foreach ($tree as &$item) {
                if (key_exists($child, $item))
                    $item[$child] = tree2tree($item[$child], $depth - 1, $child);
                else $item[$child] = [];
            }

        return $result;
    }
}

if (!function_exists('auth')) {
    /**
     * @return \app\common\authentication\AuthenticationManager|object
     */
    function auth()
    {
        return app('authentication');
    }
}