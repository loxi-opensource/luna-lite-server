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
 * SwapTemplate验证器
 * Class SwapTemplateValidate
 * @package App\Adminapi\Validate
 */
class SwapTemplateValidate extends BaseValidate
{
    /**
     * 设置错误信息
     * @var string[]
     */
   protected $messages = [
        'id.required' => 'id不能为空',
        'name.required' => '名称不能为空',
        'status.required' => '状态不能为空',
        'group_id.required' => '模板分组不能为空',
        'target_image.required' => '目标图不能为空',
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
                'status' => 'required',
                'group_id' => 'required',
                'target_image' => 'required',
                'sort' => 'integer|min:0',
            ],
            'edit' => [
                'id' => 'required',
                'name' => 'required',
                'status' => 'required',
                'group_id' => 'required',
                'target_image' => 'required',
                'sort' => 'integer|min:0',
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
