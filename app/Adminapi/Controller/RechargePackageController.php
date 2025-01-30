<?php
// +----------------------------------------------------------------------
// | Luna-Swapping开源AI换脸项目Lite版本（基于likeadmin_laravel框架）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | Github开源仓库：https://github.com/loxi-opensource/luna-lite-server
// | 全栈开发框架：https://github.com/1nFrastr/likeadmin_laravel
// | 独立开发者博客：https://www.sodair.top
// +----------------------------------------------------------------------
// | author: 1nFrastr x likeadminTeam
// +----------------------------------------------------------------------


namespace App\Adminapi\Controller;


use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\RechargePackageLists;
use App\Adminapi\Logic\RechargePackageLogic;
use App\Adminapi\Validate\RechargePackageValidate;


/**
 * RechargePackage控制器
 * Class RechargePackageController
 * @package App\Adminapi\Controller
 */
class RechargePackageController extends BaseAdminController
{


    /**
     * @notes 获取列表
     * @author likeadmin
     * @date 2025/01/30 16:55
     */
    public function lists()
    {
        return $this->dataLists(new RechargePackageLists());
    }


    /**
     * @notes 添加
     * @author likeadmin
     * @date 2025/01/30 16:55
     */
    public function add()
    {
        $params = (new RechargePackageValidate())->post()->goCheck('add');
        $result = RechargePackageLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(RechargePackageLogic::getError());
    }


    /**
     * @notes 编辑
     * @author likeadmin
     * @date 2025/01/30 16:55
     */
    public function edit()
    {
        $params = (new RechargePackageValidate())->post()->goCheck('edit');
        $result = RechargePackageLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(RechargePackageLogic::getError());
    }


    /**
     * @notes 删除
     * @author likeadmin
     * @date 2025/01/30 16:55
     */
    public function delete()
    {
        $params = (new RechargePackageValidate())->post()->goCheck('delete');
        RechargePackageLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @author likeadmin
     * @date 2025/01/30 16:55
     */
    public function detail()
    {
        $params = (new RechargePackageValidate())->goCheck('detail');
        $result = RechargePackageLogic::detail($params);
        return $this->data($result);
    }


}
