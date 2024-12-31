<?php

namespace App\Api\Validate;

use App\Common\Validate\BaseValidate;

class FeedbackValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'add' => [
                'type' => 'required|in:1,2,3',
                'content' => 'required',
                'mobile' => 'required',
                'images' => 'array|max:5',
                'images.*' => 'integer|min:1',
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'type.required' => '请选择反馈类型',
        'type.in' => '反馈类型值错误',
        'content.required' => '请输入反馈内容',
        'mobile.required' => '请输入联系方式',
        'images.array' => '图片必须是数组格式',
        'images.max' => '最多上传5张图片',
        'images.*.integer' => '图片ID必须是整数',
        'images.*.min' => '图片ID必须大于0',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
