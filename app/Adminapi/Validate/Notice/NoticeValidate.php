<?php

namespace App\Adminapi\Validate\Notice;

use App\Common\Validate\BaseValidate;

/**
 * 通知验证
 */
class NoticeValidate extends BaseValidate
{
    protected $messages = [
        'id.required' => '参数缺失',
    ];

    public function rules($scene = '')
    {
        $rules = [
            'detail' => [
                'id' => 'required',
            ],
        ];

        return $rules[$scene] ?? [];
    }
}
