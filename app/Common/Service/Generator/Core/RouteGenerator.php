<?php

declare(strict_types=1);

namespace App\Common\Service\Generator\Core;

use Illuminate\Support\Str;

/**
 * 路由生成器
 */
class RouteGenerator extends BaseGenerator implements GenerateInterface
{

    /**
     * @notes 替换变量
     * @return mixed|void
     * @author 段誉
     * @date 2022/6/22 18:14
     */
    public function replaceVariables()
    {
        // 需要替换的变量
        $needReplace = [
            '{USE}',
            '{UPPER_CAMEL_NAME}',
            '{MODULE_NAME}',
            '{PACKAGE_NAME}',
            '{NOTES}',
            '{AUTHOR}',
            '{DATE}',
            '{ROUTE}',
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getUseContent(),
            $this->getUpperCamelName(),
            $this->moduleName,
            $this->getPackageNameContent(),
            $this->tableData['class_comment'],
            $this->getAuthorContent(),
            $this->getNoteDateContent(),
            $this->getRouteContent(),
        ];

        $templatePath = $this->getTemplatePath('php/Route');

        // 替换内容
        $content = $this->replaceFileData($needReplace, $waitReplace, $templatePath);

        $this->setContent($content);
    }

    /**
     * @notes 获取use内容
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:14
     */
    public function getUseContent()
    {
        $tpl = "use App\\" . $this->moduleName . "\\Controller\\" . $this->getUpperCamelName() . 'Controller;';
        if (!empty($this->classDir)) {
            $tpl = "use App\\" . $this->moduleName . "\\Controller\\" . $this->classDir . "\\" . $this->getUpperCamelName() . 'Controller;';
        }
        return $tpl;
    }


    /**
     * @notes 获取包名
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:14
     */
    public function getPackageNameContent()
    {
        return !empty($this->classDir) ? '\\' . $this->classDir : '';
    }


    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:15
     */
    public function getModuleGenerateDir()
    {
        $dir = $this->basePath . $this->moduleName . '/Route/';
        if (!empty($this->classDir)) {
            $dir .= Str::snake($this->classDir) . '/';
            $this->checkDir($dir);
        }
        return $dir;
    }


    /**
     * @notes 获取文件生成到runtime的文件夹路径
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:15
     */
    public function getRuntimeGenerateDir()
    {
        $dir = $this->generatorDir . 'php/app/' . $this->moduleName . '/Route/';
        $this->checkDir($dir);
        if (!empty($this->classDir)) {
            $dir .= Str::snake($this->classDir) . '/';
            $this->checkDir($dir);
        }
        return $dir;
    }


    /**
     * @notes 生成的文件名
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:15
     */
    public function getGenerateName()
    {
        return $this->getTableName() . '.php';
    }


    /**
     * @notes 获取路由目录
     * @return string
     * @date 2022/6/22 18:15
     */
    public function getRouteContent()
    {
        $content = $this->getTableName();
        if (!empty($this->classDir)) {
            $content = Str::snake($this->classDir) . '.' . $this->getTableName();
        }
        return Str::lower($content);
    }

    /**
     * @notes 文件信息
     * @return array
     * @author 段誉
     * @date 2022/6/23 15:57
     */
    public function fileInfo(): array
    {
        return [
            'name' => $this->getGenerateName(),
            'type' => 'php',
            'content' => $this->content
        ];
    }

}
