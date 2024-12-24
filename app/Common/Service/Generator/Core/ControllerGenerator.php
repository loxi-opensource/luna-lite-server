<?php

declare(strict_types=1);

namespace App\Common\Service\Generator\Core;

/**
 * 控制器生成器
 */
class ControllerGenerator extends BaseGenerator implements GenerateInterface
{

    /**
     * @notes 替换变量
     * @return mixed|void
     * @author 段誉
     * @date 2022/6/22 18:09
     */
    public function replaceVariables()
    {
        // 需要替换的变量
        $needReplace = [
            '{NAMESPACE}',
            '{USE}',
            '{CLASS_COMMENT}',
            '{UPPER_CAMEL_NAME}',
            '{MODULE_NAME}',
            '{PACKAGE_NAME}',
            '{EXTENDS_CONTROLLER}',
            '{NOTES}',
            '{AUTHOR}',
            '{DATE}'
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getNameSpaceContent(),
            $this->getUseContent(),
            $this->getClassCommentContent(),
            $this->getUpperCamelName(),
            $this->moduleName,
            $this->getPackageNameContent(),
            $this->getExtendsControllerContent(),
            $this->tableData['class_comment'],
            $this->getAuthorContent(),
            $this->getNoteDateContent(),
        ];

        $templatePath = $this->getTemplatePath('php/Controller');

        // 替换内容
        $content = $this->replaceFileData($needReplace, $waitReplace, $templatePath);

        $this->setContent($content);
    }


    /**
     * @notes 获取命名空间内容
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:10
     */
    public function getNameSpaceContent()
    {
        if (!empty($this->classDir)) {
            return "namespace App\\" . $this->moduleName . "\\Controller\\" . $this->classDir . ';';
        }
        return "namespace App\\" . $this->moduleName . "\\Controller;";
    }


    /**
     * @notes 获取use模板内容
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:10
     */
    public function getUseContent()
    {
        if ($this->moduleName == 'Adminapi') {
            $tpl = "use App\\" . $this->moduleName . "\\Controller\\BaseAdminController;" . PHP_EOL;
        } else {
            $tpl = "use App\\Common\\Controller\\BaseLikeAdminController;" . PHP_EOL;
        }

        if (!empty($this->classDir)) {
            $tpl .= "use App\\" . $this->moduleName . "\\Lists\\" . $this->classDir . "\\" . $this->getUpperCamelName() . "Lists;" . PHP_EOL .
                "use App\\" . $this->moduleName . "\\Logic\\" . $this->classDir . "\\" . $this->getUpperCamelName() . "Logic;" . PHP_EOL .
                "use App\\" . $this->moduleName . "\\Validate\\" . $this->classDir . "\\" . $this->getUpperCamelName() . "Validate;";
        } else {
            $tpl .= "use App\\" . $this->moduleName . "\\Lists\\" . $this->getUpperCamelName() . "Lists;" . PHP_EOL .
                "use App\\" . $this->moduleName . "\\Logic\\" . $this->getUpperCamelName() . "Logic;" . PHP_EOL .
                "use App\\" . $this->moduleName . "\\Validate\\" . $this->getUpperCamelName() . "Validate;";
        }

        return $tpl;
    }


    /**
     * @notes 获取类描述内容
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:10
     */
    public function getClassCommentContent()
    {
        if (!empty($this->tableData['class_comment'])) {
            $tpl = $this->tableData['class_comment'] . '控制器';
        } else {
            $tpl = $this->getUpperCamelName() . '控制器';
        }
        return $tpl;
    }


    /**
     * @notes 获取包名
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:10
     */
    public function getPackageNameContent()
    {
        return !empty($this->classDir) ? '\\' . $this->classDir : '';
    }


    /**
     * @notes 获取继承控制器
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:10
     */
    public function getExtendsControllerContent()
    {
        $tpl = 'BaseAdminController';
        if ($this->moduleName != 'Adminapi') {
            $tpl = 'BaseLikeAdminController';
        }
        return $tpl;
    }


    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:10
     */
    public function getModuleGenerateDir()
    {
        $dir = $this->basePath . $this->moduleName . '/Controller/';
        if (!empty($this->classDir)) {
            $dir .= $this->classDir . '/';
            $this->checkDir($dir);
        }
        return $dir;
    }


    /**
     * @notes 获取文件生成到runtime的文件夹路径
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:11
     */
    public function getRuntimeGenerateDir()
    {
        $dir = $this->generatorDir . 'php/app/' . $this->moduleName . '/Controller/';
        $this->checkDir($dir);
        if (!empty($this->classDir)) {
            $dir .= $this->classDir . '/';
            $this->checkDir($dir);
        }
        return $dir;
    }


    /**
     * @notes 生成文件名
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:11
     */
    public function getGenerateName()
    {
        return $this->getUpperCamelName() . 'Controller.php';
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
