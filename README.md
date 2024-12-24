<h1 align="center" style="margin: 10px 0 10px; font-weight: bold;">likeadmin_laravel</h1>
<h3 align="center" style="margin-bottom: 10px;">用Laravel 11重写likeadmin前后端分离全栈开发框架</h3>
<p align="center">
<a href="#"><img src="https://img.shields.io/badge/Laravel-11-ef6763"></a>
<a href="#"><img src="https://img.shields.io/badge/PHP-8.2-8892bf"></a>
<a href="#"><img src="https://img.shields.io/badge/MySQL-8.0-3e6e93"></a>

来源项目：[likeadmin_php](https://github.com/likeadmin-likeshop/likeadmin_php) ( v1.9.4 / bfba16334f)

当前进度：
█████████░ 99%

技术栈：
- PHP 8.0 => PHP 8.2
- ThinkPHP 8 => Laravel 11
- 管理后台：Vue3 + TypeScript + ElementPlus UI + TailwindCSS
- 小程序：Vue3 + TypeScript + Uniapp + TailwindCSS
- PC端：Vue3 + Nuxt

### 设计目标

1. 完全兼容likeadmin_php数据库表结构、API接口路由等

2. 无需改动任何一行前端代码/任何一个表结构，即可无缝迁移到Laravel

### 优先级排期

- [x] 代码结构迁移：遵循 PSR-4
- [x] 核心迁移：路由、中间件、响应封装器
- [x] 基类改造：列表查询类、缓存类、多场景验证器
- [x] 管理端 API - 管理员登录
- [x] 管理端 API - 工作台数据、后台基础配置接口
- [x] 管理端 API - 管理员/角色/菜单权限管理
- [x] 权限控制中间件
- [x] 数据表格导出
- [x] 静态资源放到项目工程
- [x] 数据库模型兼容TP框架：软删除、创建时间、更新时间
- [x] 管理端 - 文章资讯管理
- [x] 移动端 - 账号登录注册
- [x] 移动端 - 第三方登录：手机验证码登录
- [x] 系统设置 - 用户设置、网站设置
- [x] 素材中心 - 文件管理
- [x] 移动端 - 账号信息变更：绑定手机号、修改密码等
- [x] 移动端 - 文章资讯
- [x] 管理端 - 部门岗位管理
- [x] 操作日志Listener等监听器逻辑迁移
- [x] 装修管理
- [x] 代码生成器
- [x] 安装引导UI、Release发行版
- [ ] 渠道设置：微信小程序配置✔、公众号菜单管理✔、公众号消息回复逻辑✔、h5设置✔、开放平台TODO
- [ ] 第三方登录：微信小程序授权登录✔、H5公众号授权登录✔、PC端扫码登录TODO（需配合开放平台）
- [ ] 存储引擎 - 文件上传：本地存储✔、阿里云、腾讯云、七牛云
- [ ] 钱包充值✔、微信支付（小程序支付✔、公众号/H5付款暂未测试）、支付宝支付TODO

### API接口迁移工作清单：

| **管理后台**   | 进度 | **移动端**     | 进度 | **PC端**       | 进度 |
|----------------|----|----------------|----|----------------|------|
| 工作台 | ✔ | 登录注册 | ✔ | 首页数据 | ✔ |
| 登录 | ✔ | 文章管理 | ✔ | 网站配置 | ✔ |
| 装修管理 | ✔ | 上传管理 | ✔ | 资讯中心 | ✔ |
| 文章资讯 | ✔ | 用户管理 | ✔ | 文章详情 | ✔ |
| 消息通知 | ✔ | 用户钱包 | ✔ | 扫码登录 |      |
| 渠道设置 | ✔ | 支付相关 | ✔ |                |      |
| 组织管理 | ✔ | 其他 | ✔ |                |      |
| 权限管理 | ✔ |                |    |                |      |
| 系统设置 |    |                |    |                |      |
| 文件管理 | ✔ |                |    |                |      |
| 存储引擎 |    |                |    |                |      |
| 开发工具 | ✔ |                |    |                |      |
| 用户管理 | ✔ |                |    |                |      |
| 通用数据 | ✔ |                |    |                |      |
| 营销应用 | ✔ |                |    |                |      |
| 财务管理 | ✔ |                |    |                |      |

### 宝塔部署指南

伪静态规则
    
 ```
location / {
  try_files $uri $uri/ /index.php?$query_string;
}

location /mobile {
  try_files $uri $uri/ /mobile/index.html;
}

location /pc {
  try_files $uri $uri/ /pc/index.html;
}
```

移除PHP禁用函数symlink。然后执行命令：`php artisan storage:link`

该命令创建了一个软链接到由public/storage指向storage/app/public目录，使后者目录下的文件可以通过HTTP访问。
