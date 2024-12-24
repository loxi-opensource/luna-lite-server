<?php

declare(strict_types=1);

namespace App\Common\Service\Generator\Core;

/**
 * 验证器生成器
 */
class ValidateGenerator extends BaseGenerator implements GenerateInterface
{
    /**
     * @notes 替换变量
     * @return mixed|void
     * @author 段誉
     * @date 2022/6/22 18:18
     */
    public function replaceVariables()
    {
        // 需要替换的变量
        $needReplace = [
            '{NAMESPACE}',
            '{CLASS_COMMENT}',
            '{UPPER_CAMEL_NAME}',
            '{MODULE_NAME}',
            '{PACKAGE_NAME}',
            '{PK}',
            '{NOTES}',
            '{AUTHOR}',
            '{DATE}',
            '{ADD_RULES}',
            '{EDIT_RULES}',
            '{FIELD}',
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getNameSpaceContent(),
            $this->getClassCommentContent(),
            $this->getUpperCamelName(),
            $this->moduleName,
            $this->getPackageNameContent(),
            $this->getPkContent(),
            $this->tableData['class_comment'],
            $this->getAuthorContent(),
            $this->getNoteDateContent(),
            $this->getAddRulesContent(),
            $this->getEditRulesContent(),
            $this->getFiledContent(),
        ];

        $templatePath = $this->getTemplatePath('php/Validate');

        // 替换内容
        $content = $this->replaceFileData($needReplace, $waitReplace, $templatePath);

        $this->setContent($content);
    }

    /**
     * @notes 添加场景验证参数
     */
    public function getAddRulesContent()
    {
        $content = "";
        foreach ($this->tableColumn as $column) {
            if ($column['is_required'] == 1 && $column['column_name'] != $this->getPkContent()) {
                $content .= "'" . $column['column_name'] . "' => 'required'," . PHP_EOL;
            }
        }

        $content = trim($content);
        return $this->setBlankSpace($content, "                ");
    }


    /**
     * @notes 编辑场景验证参数
     * @return string
     * @author 段誉
     * @date 2022/12/7 15:20
     */
    public function getEditRulesContent()
    {
        $content = '';
        foreach ($this->tableColumn as $column) {
            if ($column['is_required'] == 1) {
                $content .= "'" . $column['column_name'] . "' => 'required'," . PHP_EOL;
            }
        }

        $content = trim($content);
        return $this->setBlankSpace($content, "                ");
    }


    /**
     * @notes 验证字段描述
     * @return string
     * @author 段誉
     * @date 2022/12/9 15:09
     */
    public function getFiledContent()
    {
        $content = "";
        foreach ($this->tableColumn as $column) {
            if ($column['is_required'] == 1) {
                $columnComment = $column['column_comment'];
                if (empty($column['column_comment'])) {
                    $columnComment = $column['column_name'];
                }
                $content .= "'" . $column['column_name'] . ".required' => '" . $columnComment . "不能为空'," . PHP_EOL;
            }
        }

        $content = trim($content);
        return $this->setBlankSpace($content, "        ");
    }


    /**
     * @notes 获取命名空间模板内容
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:18
     */
    public function getNameSpaceContent()
    {
        if (!empty($this->classDir)) {
            return "namespace App\\" . $this->moduleName . "\\Validate\\" . $this->classDir . ';';
        }
        return "namespace App\\" . $this->moduleName . "\\Validate;";
    }


    /**
     * @notes 获取类描述
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:18
     */
    public function getClassCommentContent()
    {
        if (!empty($this->tableData['class_comment'])) {
            $tpl = $this->tableData['class_comment'] . '验证器';
        } else {
            $tpl = $this->getUpperCamelName() . '验证器';
        }
        return $tpl;
    }


    /**
     * @notes 获取包名
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:18
     */
    public function getPackageNameContent()
    {
        return !empty($this->classDir) ? '\\' . $this->classDir : '';
    }


    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:18
     */
    public function getModuleGenerateDir()
    {
        $dir = $this->basePath . $this->moduleName . '/Validate/';
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
     * @date 2022/6/22 18:18
     */
    public function getRuntimeGenerateDir()
    {
        $dir = $this->generatorDir . 'php/app/' . $this->moduleName . '/Validate/';
        $this->checkDir($dir);
        if (!empty($this->classDir)) {
            $dir .= $this->classDir . '/';
            $this->checkDir($dir);
        }
        return $dir;
    }


    /**
     * @notes 生成的文件名
     * @return string
     * @author 段誉
     * @date 2022/6/22 18:19
     */
    public function getGenerateName()
    {
        return $this->getUpperCamelName() . 'Validate.php';
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
