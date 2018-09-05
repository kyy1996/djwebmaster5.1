<?php

namespace app\api\controller;


use app\common\model\Activity;
use think\Request;

class Activities extends Controller
{
    /**
     * 显示资源列表
     *
     * @return void
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $list = Activity::all(null, ['comments', 'attendances', 'enrollments', 'article']);
        $this->result($list);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return void
     */
    public function save(Request $request)
    {
        $activity = Activity::create($request->request());
        $this->result($activity);
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return void
     * @throws \think\exception\DbException
     */
    public function read($id)
    {
        $activity = Activity::getOrFail($id, ['comments', 'attendances', 'enrollments', 'article']);
        $this->result($activity);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int            $id
     * @return void
     * @throws \think\exception\DbException
     */
    public function update(Request $request, $id)
    {
        $activity = Activity::getOrFail($id, ['comments', 'attendances', 'enrollments', 'article']);
        $activity->save($request->request());
        $this->result($activity, 0, lang('更新成功'));
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return void
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        $activity = Activity::getOrFail($id, ['comments', 'attendances', 'enrollments', 'article']);
        $activity->delete();
        $this->result([], 0, lang('删除成功'));
    }
}
