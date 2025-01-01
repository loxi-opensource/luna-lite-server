<?php

namespace App\Adminapi\Logic;

use App\Common\Logic\BaseLogic;
use App\Common\Service\ConfigService;
use App\Common\Service\FileService;

/**
 * 工作台
 */
class WorkbenchLogic extends BaseLogic
{
    /**
     * @notes 工作套
     * @param $adminInfo
     * @return array
     */
    public static function index()
    {
        return [
            // 版本信息
            'version' => self::versionInfo(),
            // 今日数据
            'today' => self::today(),
            // 常用功能
            'menu' => self::menu(),
            // 近15日访客数
            'visitor' => self::visitor(),
            // 服务支持
            'support' => self::support(),
            // 销售数据
            'sale' => self::sale()
        ];
    }


    /**
     * @notes 常用功能
     * @return array[]
     */
    public static function menu(): array
    {
        return [
            [
                'name' => '管理员',
                'image' => FileService::getFileUrl(config('project.default_image.menu_admin')),
                'url' => '/permission/admin'
            ],
            [
                'name' => '角色管理',
                'image' => FileService::getFileUrl(config('project.default_image.menu_role')),
                'url' => '/permission/role'
            ],
            [
                'name' => '部门管理',
                'image' => FileService::getFileUrl(config('project.default_image.menu_dept')),
                'url' => '/organization/department'
            ],
            [
                'name' => '字典管理',
                'image' => FileService::getFileUrl(config('project.default_image.menu_dict')),
                'url' => '/dev_tools/dict'
            ],
            [
                'name' => '代码生成器',
                'image' => FileService::getFileUrl(config('project.default_image.menu_generator')),
                'url' => '/dev_tools/code'
            ],
            [
                'name' => '素材中心',
                'image' => FileService::getFileUrl(config('project.default_image.menu_file')),
                'url' => '/material/index'
            ],
            [
                'name' => '菜单权限',
                'image' => FileService::getFileUrl(config('project.default_image.menu_auth')),
                'url' => '/permission/menu'
            ],
            [
                'name' => '网站信息',
                'image' => FileService::getFileUrl(config('project.default_image.menu_web')),
                'url' => '/setting/website/information'
            ],
        ];
    }


    /**
     * @notes 版本信息
     * @return array
     */
    public static function versionInfo(): array
    {
        return [
            'version' => config('project.version'),
            'website' => config('project.website.url'),
            'name' => ConfigService::get('website', 'name'),
            'based' => 'vue3.x、ElementUI、MySQL',
            'channel' => [
                'website' => 'https://www.sodair.top/',
                'github' => 'https://github.com/loxi-opensource/luna-lite-server',
                'framework' => 'https://github.com/1nFrastr/likeadmin_laravel',
             ]
        ];
    }


    /**
     * @notes 今日数据
     * @return int[]
     * @author 段誉
     * @date 2021/12/29 16:15
     */
    public static function today(): array
    {
        return [
            'time' => date('Y-m-d H:i:s'),
            // 今日销售额
            'today_sales' => 100,
            // 总销售额
            'total_sales' => 1000,

            // 今日访问量
            'today_visitor' => 10,
            // 总访问量
            'total_visitor' => 100,

            // 今日新增用户量
            'today_new_user' => 30,
            // 总用户量
            'total_new_user' => 3000,

            // 订单量 (笔)
            'order_num' => 12,
            // 总订单量
            'order_sum' => 255
        ];
    }


    /**
     * @notes 访问数
     * @return array
     */
    public static function visitor(): array
    {
        $num = [];
        $date = [];
        for ($i = 0; $i < 15; $i++) {
            $where_start = strtotime("- " . $i . "day");
            $date[] = date('m/d', $where_start);
            $num[$i] = rand(0, 100);
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '访客数', 'data' => $num]
            ]
        ];
    }

    /**
     * @notes 访问数
     * @return array
     */
    public static function sale(): array
    {
        $num = [];
        $date = [];
        for ($i = 0; $i < 7; $i++) {
            $where_start = strtotime("- " . $i . "day");
            $date[] = date('m/d', $where_start);
            $num[$i] = rand(30, 200);
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '销售量', 'data' => $num]
            ]
        ];
    }


    /**
     * @notes 服务支持
     * @return array[]
     */
    public static function support()
    {
        return [
            [
                'image' => FileService::getFileUrl(config('project.default_image.qq_group')),
                'title' => '官方公众号',
                'desc' => '关注官方公众号',
            ],
            [
                'image' => FileService::getFileUrl(config('project.default_image.customer_service')),
                'title' => '添加企业客服微信',
                'desc' => '想了解更多请添加客服',
            ]
        ];
    }

}
