<?php

namespace app\api\controller;

use app\common\model\CommonConfig;
use think\Request;

class Config extends Controller
{
    /**
     * 显示资源列表
     *
     * @return void
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $this->result(CommonConfig::all());
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     * @throws \think\exception\DbException
     */
    public function save(Request $request)
    {
        $list = CommonConfig::setProperty($request->request());
        $this->result(CommonConfig::getProperty(), 0, '设置成功');
    }

    /**
     * 显示指定的资源
     *
     * @param $name
     * @return void
     * @throws \think\exception\DbException
     */
    public function read($name)
    {
        $value = CommonConfig::getProperty($name);
        $this->result($value);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @return void
     * @throws \think\exception\DbException
     */
    public function update(Request $request)
    {
        $this->save($request);
    }

    /**
     * 删除指定资源
     *
     * @param  int $name
     * @return void
     * @throws \think\exception\DbException
     */
    public function delete($name)
    {
        $data = CommonConfig::getOrFail($name);
        $this->result($data);
    }
}
