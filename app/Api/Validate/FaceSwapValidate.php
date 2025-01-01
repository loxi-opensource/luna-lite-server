<?php

namespace App\Api\Validate;

use App\Common\Validate\BaseValidate;

class FaceSwapValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'createSwap' => [
                'template_id' => 'required|exists:swap_template,id',
                'avatar_id' => 'required|exists:digital_avatar,id',
            ],
            'removeRecord' => [
                'id' => 'required|exists:swap_record,id',
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'template_id.required' => 'template_id必填',
        'avatar_id.required' => 'avatar_id必填',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
