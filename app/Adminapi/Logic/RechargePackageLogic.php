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

namespace App\Adminapi\Logic;


use App\Common\Model\RechargePackage;
use App\Common\Logic\BaseLogic;
use Illuminate\Support\Facades\DB;


/**
 * RechargePackage逻辑
 * Class RechargePackageLogic
 * @package App\Adminapi\Logic
 */
class RechargePackageLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2025/01/30 16:55
     */
    public static function add(array $params): bool
    {
        DB::beginTransaction();
        try {
            RechargePackage::create([
                'name' => $params['name'],
                'describe' => $params['describe'],
                'sell_price' => $params['sell_price'],
                'draw_number' => $params['draw_number'],
                'sort' => $params['sort'],
                'status' => $params['status']
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
     * @date 2025/01/30 16:55
     */
    public static function edit(array $params): bool
    {
        DB::beginTransaction();
        try {

            $model = RechargePackage::findOrFail($params['id']);
            $model->fill([
                'name' => $params['name'],
                'describe' => $params['describe'],
                'sell_price' => $params['sell_price'],
                'draw_number' => $params['draw_number'],
                'sort' => $params['sort'],
                'status' => $params['status']
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
     * @date 2025/01/30 16:55
     */
    public static function delete(array $params): bool
    {
        return RechargePackage::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2025/01/30 16:55
     */
    public static function detail($params): array
    {
        return RechargePackage::findOrFail($params['id'])->toArray();
    }
}
