<?php

namespace App\Adminapi\Validate\Channel;

use App\Common\Validate\BaseValidate;

/**
 * 微信公众号回复验证器
 */
class OfficialAccountReplyValidate extends BaseValidate
{
    protected $messages = [
        'reply_type.required' => '请输入回复类型',
        'reply_type.in' => '回复类型状态值错误',
        'name.required' => '请输入规则名称',
        'content_type.required' => '请选择内容类型',
        'content_type.in' => '内容类型状态值有误',
        'content.required' => '请输入回复内容',
        'status.required' => '请选择启用状态',
        'status.in' => '启用状态值错误',
        'keyword.required_if' => '请输入关键词',
        'matching_type.required_if' => '请选择匹配类型',
        'matching_type.in' => '匹配类型状态值错误',
        'sort.required_if' => '请输入排序值',
        'sort.integer' => '排序值须为整型',
        'sort.egt' => '排序值须大于或等于0',
        'reply_num.required_if' => '请选择回复数量',
        'reply_num.in' => '回复数量状态值错误',
        'id.required' => '参数缺失',
        'id.integer' => '参数格式错误',
        'new_sort.required' => '请输入新排序值',
        'new_sort.integer' => '新排序值须为整型',
        'new_sort.egt' => '新排序值须大于或等于0',
    ];

    public function rules($scene = '')
    {
        // 定义基本的验证规则
        $rules = [
            'id' => 'required|integer',
            'reply_type' => 'required|in:1,2,3',
            'name' => 'required',
            'content_type' => 'required|in:1',
            'content' => 'required',
            'status' => 'required|in:0,1',
            'keyword' => 'required_if:reply_type,2',
            'matching_type' => 'required_if:reply_type,2|in:1,2',
            'sort' => 'required_if:reply_type,2|integer|gte:0',
            'reply_num' => 'required_if:reply_type,2|in:1',
            'new_sort' => 'required|integer|gte:0',
        ];

        // 定义场景
        $scenes = [
            'add' => ['reply_type', 'name', 'content_type', 'content', 'status', 'keyword', 'matching_type', 'sort', 'reply_num'],
            'detail' => ['id'],
            'delete' => ['id'],
            'sort' => ['id', 'new_sort'],
            'status' => ['id'],
            'edit' => ['id', 'reply_type', 'name', 'content_type', 'content', 'status', 'keyword', 'matching_type', 'sort', 'reply_num'],
        ];

        // 根据场景返回相应的规则
        if (isset($scenes[$scene])) {
            return array_intersect_key($rules, array_flip($scenes[$scene]));
        }

        return $rules; // 默认返回所有规则
    }

}
