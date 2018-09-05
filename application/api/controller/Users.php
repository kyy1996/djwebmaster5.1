<?php

namespace app\api\controller;

use app\common\model\User;
use think\Request;

class Users extends Controller
{
    /**
     * 显示资源列表
     *
     * @return void
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $list = User::all(null, ['userProfile', 'userGroups', 'publishedJobs', 'jobApplications', 'publishArticles', 'updateArticles', 'photos', 'attachments']);
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
        $user = new User();
        $user::create($request->request());
        $this->result(lang('用户保存成功 '));
    }

    /**
     * 显示指定的资源
     *
     * @param Request $request
     * @param User    $user
     * @return void
     */
    public function read(Request $request, User $user)
    {
        $this->result($user);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int            $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * 删除指定资源
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user)
    {
        $user->delete();
        $this->result(lang('用户删除成功'));
    }
}
