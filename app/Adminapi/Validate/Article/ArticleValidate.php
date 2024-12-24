<?php

namespace App\Adminapi\Validate\Article;

use App\Common\Validate\BaseValidate;

/**
 * 资讯管理验证
 */
class ArticleValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'add' => [
                'title' => 'required|string|between:1,255',
                'cid' => 'required',
                'is_show' => 'required|in:0,1',
            ],
            'detail' => [
                'id' => 'required|exists:article,id',
            ],
            'status' => [
                'id' => 'required|exists:article,id',
                'is_show' => 'required|in:0,1',
            ],
            'edit' => [
                'id' => 'required|exists:article,id',
                'title' => 'required|string|between:1,255',
                'cid' => 'required',
                'is_show' => 'required|in:0,1',
            ],
            'delete' => [
                'id' => 'required|exists:article,id',
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'id.required' => '资讯id不能为空',
        'title.required' => '标题不能为空',
        'title.between' => '标题长度须在1-255位字符',
        'cid.required' => '所属栏目必须存在',
        'is_show.required' => '是否显示不能为空',
        'id.exists' => '资讯不存在',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
