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

namespace App\Adminapi\Validate;

use App\Common\Validate\BaseValidate;

/**
 * RechargePackage验证器
 * Class RechargePackageValidate
 * @package App\Adminapi\Validate
 */
class RechargePackageValidate extends BaseValidate
{
    /**
     * 设置错误信息
     * @var string[]
     */
   protected $messages = [
        'id.required' => 'id不能为空',
        'name.required' => '套餐名称不能为空',
        'describe.required' => '套餐描述不能为空',
        'sell_price.required' => '套餐价格不能为空',
        'draw_number.required' => '作图次数不能为空',
        'sort.required' => '排序不能为空',
        'status.required' => '套餐状态不能为空',
   ];

    /**
     * 设置校验规则
     * @param $scene
     * @return array
     */
    public function rules($scene = '')
    {
        $rules = [
            'add' => [
                'name' => 'required',
                'describe' => 'required',
                'sell_price' => 'required',
                'draw_number' => 'required',
                'sort' => 'required',
                'status' => 'required',
            ],
            'edit' => [
                'id' => 'required',
                'name' => 'required',
                'describe' => 'required',
                'sell_price' => 'required',
                'draw_number' => 'required',
                'sort' => 'required',
                'status' => 'required',
            ],
            'detail' => [
                'id' => 'required',
            ],
            'delete' => [
                'id' => 'required',
            ],
        ];

        return $rules[$scene] ?? [];
    }

}
