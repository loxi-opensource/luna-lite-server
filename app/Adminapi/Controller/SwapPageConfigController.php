<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------


namespace App\Adminapi\Controller;


use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\SwapPageConfigLists;
use App\Adminapi\Logic\SwapPageConfigLogic;
use App\Adminapi\Validate\SwapPageConfigValidate;


/**
 * 换脸页面配置控制器
 * Class SwapPageConfigController
 * @package App\Adminapi\Controller
 */
class SwapPageConfigController extends BaseAdminController
{


    /**
     * @notes 获取换脸页面配置列表
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public function lists()
    {
        return $this->dataLists(new SwapPageConfigLists());
    }


    /**
     * @notes 添加换脸页面配置
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public function add()
    {
        $params = (new SwapPageConfigValidate())->post()->goCheck('add');
        $result = SwapPageConfigLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(SwapPageConfigLogic::getError());
    }


    /**
     * @notes 编辑换脸页面配置
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public function edit()
    {
        $params = (new SwapPageConfigValidate())->post()->goCheck('edit');
        $result = SwapPageConfigLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(SwapPageConfigLogic::getError());
    }


    /**
     * @notes 删除换脸页面配置
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public function delete()
    {
        $params = (new SwapPageConfigValidate())->post()->goCheck('delete');
        SwapPageConfigLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取换脸页面配置详情
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public function detail()
    {
        $params = (new SwapPageConfigValidate())->goCheck('detail');
        $result = SwapPageConfigLogic::detail($params);
        return $this->data($result);
    }


}
