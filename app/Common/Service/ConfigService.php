<?php


namespace App\Common\Service;

use App\Common\Model\Config;

class ConfigService
{
    /**
     * @notes 设置配置值
     */
    public static function set(string $type, string $name, $value)
    {
        $original = $value;
        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        $data = Config::query()->where(['type' => $type, 'name' => $name])->first();

        if (!$data) {
            Config::query()->create([
                'type' => $type,
                'name' => $name,
                'value' => $value,
            ]);
        } else {
            $data->value = $value;
            $data->save();
        }

        // 返回原始值
        return $original;
    }

    /**
     * @notes 获取配置值
     */
    public static function get(string $type, string $name = '', $default_value = null)
    {
        if (!empty($name)) {
            $value = Config::where(['type' => $type, 'name' => $name])->value('value');
            if (!is_null($value)) {
                $json = json_decode($value, true);
                $value = json_last_error() === JSON_ERROR_NONE ? $json : $value;
            }
            if ($value) {
                return $value;
            }
            // 返回特殊值 0 '0'
            if ($value === 0 || $value === '0') {
                return $value;
            }
            // 返回默认值
            if ($default_value !== null) {
                return $default_value;
            }
            // 返回本地配置文件中的值
            return config('project.' . $type . '.' . $name);
        }

        // 取某个类型下的所有name的值
        $data = Config::query()->where(['type' => $type])->pluck('value', 'name');
        foreach ($data as $k => $v) {
            $json = json_decode($v, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data[$k] = $json;
            }
        }
        if ($data) {
            return $data;
        }
    }
}
