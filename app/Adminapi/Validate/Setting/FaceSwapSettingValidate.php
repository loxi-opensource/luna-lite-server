<?php

namespace App\Adminapi\Validate\Setting;

use App\Common\Validate\BaseValidate;

class FaceSwapSettingValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'setFaceSwapKey' => [
                'ak' => 'required',
                'sk' => 'required'
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'ak.required' => 'ak必填',
        'sk.required' => 'sk必填',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
