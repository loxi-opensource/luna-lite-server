<?php

namespace App\Adminapi\Validate\Decorate;

use App\Common\Validate\BaseValidate;

class DecoratePageValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        return [
            'id' => 'required',
            'type' => 'required',
            'data' => 'required',
        ];
    }

    protected $messages = [
        'id.required' => '参数缺失',
        'type.required' => '装修类型参数缺失',
        'data.required' => '装修信息参数缺失',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
