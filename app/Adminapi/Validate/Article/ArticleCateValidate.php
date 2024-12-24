<?php

namespace App\Adminapi\Validate\Article;

use App\Common\Model\Article\Article;
use App\Common\Validate\BaseValidate;
use Closure;

/**
 * 资讯分类管理验证
 */
class ArticleCateValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'add' => [
                'name' => 'required|string|between:1,90',
                'is_show' => 'required|in:0,1',
                'sort' => 'nullable|integer|min:0',
            ],
            'detail' => [
                'id' => 'required|exists:article_cate,id',
            ],
            'status' => [
                'id' => 'required|exists:article_cate,id',
                'is_show' => 'required|in:0,1',
            ],
            'edit' => [
                'id' => 'required|exists:article_cate,id',
                'name' => 'required|string|between:1,90',
                'is_show' => 'required|in:0,1',
                'sort' => 'nullable|integer|min:0',
            ],
            'delete' => [
                'id' => [
                    'required',
                    'exists:article_cate,id',
                    function ($attribute, $value, Closure $fail) {
                        $article = Article::where('cid', $value)->exists();
                        if ($article) {
                            return $fail('资讯分类已使用，请先删除绑定该资讯分类的资讯');
                        }
                    },
                ],
            ],
            'select' => [
                'type' => 'nullable|string',
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'id.required' => '资讯分类id不能为空',
        'name.required' => '资讯分类不能为空',
        'name.between' => '资讯分类长度须在1-90位字符',
        'sort.min' => '排序值不正确',
        'id.exists' => '资讯分类不存在',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
