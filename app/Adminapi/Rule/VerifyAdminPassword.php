<?php

namespace App\Adminapi\Rule;

use App\Common\Cache\AdminAccountSafeCache;
use App\Common\Model\Auth\Admin;
use App\Common\Service\ConfigService;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;

class VerifyAdminPassword implements DataAwareRule, ValidationRule
{
    /**
     * All the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];


    /**
     * Set the data under validation.
     *
     * @param array<string, mixed> $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $errMsg = $this->validatePassword($this->data['account'], $value);
        if ($errMsg !== true) {
            $fail($errMsg);
        }
    }

    protected function validatePassword($account, $password)
    {
        $config = [
            'login_restrictions' => ConfigService::get('admin_login', 'login_restrictions'),
            'password_error_times' => ConfigService::get('admin_login', 'password_error_times'),
            'limit_login_time' => ConfigService::get('admin_login', 'limit_login_time'),
        ];

        $adminAccountSafeCache = new AdminAccountSafeCache();
        if ($config['login_restrictions'] == 1) {
            $adminAccountSafeCache->count = $config['password_error_times'];
            $adminAccountSafeCache->minute = $config['limit_login_time'];
        }

        if ($config['login_restrictions'] == 1 && !$adminAccountSafeCache->isSafe()) {
            return '密码连续' . $adminAccountSafeCache->count . '次输入错误，请' . $adminAccountSafeCache->minute . '分钟后重试';
        }

        $adminInfo = Admin::query()
            ->where('account', $account)
            ->select(['password', 'disable'])
            ->first();

        if (!$adminInfo) {
            return '账号不存在';
        }

        if ($adminInfo->disable === 1) {
            return '账号已禁用';
        }

        if (empty($adminInfo->password)) {
            $adminAccountSafeCache->record();
            return '账号不存在';
        }

        $passwordSalt = Config::get('project.unique_identification');
        if ($adminInfo->password !== create_password($password, $passwordSalt)) {
            $adminAccountSafeCache->record();
            return '密码错误';
        }

        $adminAccountSafeCache->relieve();
        return true;
    }

}
