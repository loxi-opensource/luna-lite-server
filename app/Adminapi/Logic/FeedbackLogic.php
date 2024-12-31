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

namespace App\Adminapi\Logic;


use App\Common\Model\Feedback;
use App\Common\Logic\BaseLogic;
use Illuminate\Support\Facades\DB;


/**
 * 用户反馈逻辑
 * Class FeedbackLogic
 * @package App\Adminapi\Logic
 */
class FeedbackLogic extends BaseLogic
{


    /**
     * @notes 添加用户反馈
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/31 15:27
     */
    public static function add(array $params): bool
    {
        DB::beginTransaction();
        try {
            Feedback::create([

            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 编辑用户反馈
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/31 15:27
     */
    public static function edit(array $params): bool
    {
        DB::beginTransaction();
        try {

            $model = Feedback::findOrFail($params['id']);
            $model->fill([

            ]);
            $model->save();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 删除用户反馈
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/31 15:27
     */
    public static function delete(array $params): bool
    {
        return Feedback::destroy($params['id']);
    }


    /**
     * @notes 获取用户反馈详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2024/12/31 15:27
     */
    public static function detail($params): array
    {
        return Feedback::findOrFail($params['id'])->toArray();
    }
}
