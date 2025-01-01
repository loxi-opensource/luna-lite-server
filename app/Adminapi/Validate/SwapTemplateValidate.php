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
