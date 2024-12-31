<?php

namespace App\Api\Validate;

use App\Common\Enum\PayEnum;
use App\Common\Validate\BaseValidate;

class SwapTemplateValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'groupList' => [
                'page_id' => 'required|exists:swap_page_config,id',
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'page_id.required' => 'page_id必填',
        'page_id.exists' => 'page_id配置不存在',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
