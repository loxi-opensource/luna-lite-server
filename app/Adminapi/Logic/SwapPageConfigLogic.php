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


use App\Common\Model\SwapPageConfig;
use App\Common\Logic\BaseLogic;
use Illuminate\Support\Facades\DB;


/**
 * 换脸页面配置逻辑
 * Class SwapPageConfigLogic
 * @package App\Adminapi\Logic
 */
class SwapPageConfigLogic extends BaseLogic
{


    /**
     * @notes 添加换脸页面配置
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public static function add(array $params): bool
    {
        DB::beginTransaction();
        try {
            SwapPageConfig::create([
                'name' => $params['name'],
                'page_data' => $params['page_data'],
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
     * @notes 编辑换脸页面配置
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public static function edit(array $params): bool
    {
        DB::beginTransaction();
        try {

            $model = SwapPageConfig::findOrFail($params['id']);
            $model->fill([
                'name' => $params['name'],
                'page_data' => $params['page_data'],
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
     * @notes 删除换脸页面配置
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public static function delete(array $params): bool
    {
        return SwapPageConfig::destroy($params['id']);
    }


    /**
     * @notes 获取换脸页面配置详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public static function detail($params): array
    {
        return SwapPageConfig::findOrFail($params['id'])->toArray();
    }
}
