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


use App\Common\Model\SwapRecord;
use App\Common\Logic\BaseLogic;
use Illuminate\Support\Facades\DB;


/**
 * SwapRecord逻辑
 * Class SwapRecordLogic
 * @package App\Adminapi\Logic
 */
class SwapRecordLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public static function add(array $params): bool
    {
        DB::beginTransaction();
        try {
            SwapRecord::create([

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
     * @notes 编辑
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public static function edit(array $params): bool
    {
        DB::beginTransaction();
        try {

            $model = SwapRecord::findOrFail($params['id']);
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
     * @notes 删除
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public static function delete(array $params): bool
    {
        return SwapRecord::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public static function detail($params): array
    {
        return SwapRecord::findOrFail($params['id'])->toArray();
    }
}
