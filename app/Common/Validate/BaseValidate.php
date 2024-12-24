<?php

namespace App\Common\Validate;

use Illuminate\Support\Facades\Validator;

class BaseValidate
{
    protected $rules = [];

    protected $messages = [];

    // 全部输入参数
    private static array $params = [];

    public function rules($scene = 'default')
    {
        return $this->rules[$scene] ?? [];
    }

    public function messages()
    {
        return $this->messages;
    }

    public static function getParam($key)
    {
        return self::$params[$key] ?? null;
    }

    private static function setParams(array $params): void
    {
        self::$params = $params;
    }

    public static function getParams(): array
    {
        return self::$params;
    }

    public function post()
    {
        return $this;
    }

    public function get()
    {
        return $this;
    }

    public function goCheck($scene = 'default', $extraData = [])
    {
        $this->scene($scene, $extraData)->validate();
        return self::getParams();
    }

    /**
     * create validator by $scene.
     *
     * @param   $scene
     * @param   $extraData
     *
     * @return \Illuminate\Validation\Validator
     */
    public function scene($scene = 'default', $extraData = [])
    {
        // Laravel验证器会过滤掉不在校验规则中出现的字段，而TP会保留。为了兼容TP，这里取全部输入。
        self::setParams(array_merge(request()->all(), $extraData));
        $instance = new static();
        return Validator::make(
            self::getParams(),
            $instance->rules($scene),
            $instance->messages()
        );
    }

}
