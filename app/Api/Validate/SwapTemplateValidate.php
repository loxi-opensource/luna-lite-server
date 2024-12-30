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
                'page_id' => 'required',
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'page_id.required' => 'page_id必填',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
