<?php

namespace App\Adminapi\Logic\Tools;

use App\Common\Enum\GeneratorEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Tools\GenerateColumn;
use App\Common\Model\Tools\GenerateTable;
use App\Common\Service\Generator\GenerateService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * 生成器逻辑
 */
class GeneratorLogic extends BaseLogic
{
    /**
     * @notes 表详情
     */
    public static function getTableDetail(array $params): array
    {
        $detail = GenerateTable::with('tableColumn')
            ->findOrFail($params['id'])
            ->toArray();

        $options = self::formatConfigByTableData($detail);
        $detail['menu'] = $options['menu'];
        $detail['delete'] = $options['delete'];
        $detail['tree'] = $options['tree'];
        $detail['relations'] = $options['relations'];
        return $detail;
    }

    /**
     * @notes 选择数据表
     */
    public static function selectTable($params, $adminId)
    {
        DB::beginTransaction();
        try {
            foreach ($params['table'] as $item) {
                // 添加主表基础信息
                $generateTable = self::initTable($item, $adminId);
                // 获取数据表字段信息
                $column = self::getTableColumn($item['name']);
                // 添加表字段信息
                self::initTableColumn($column, $generateTable['id']);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 编辑表信息
     */
    public static function editTable($params)
    {
        DB::beginTransaction();
        try {
            // 格式化配置
            $options = self::formatConfigByTableData($params);
            // 更新主表-数据表信息
            GenerateTable::where('id', $params['id'])->update([
                'table_name' => $params['table_name'],
                'table_comment' => $params['table_comment'],
                'template_type' => $params['template_type'],
                'author' => $params['author'] ?? '',
                'remark' => $params['remark'] ?? '',
                'generate_type' => $params['generate_type'],
                'module_name' => $params['module_name'],
                'class_dir' => $params['class_dir'] ?? '',
                'class_comment' => $params['class_comment'] ?? '',
                'menu' => $options['menu'],
                'delete' => $options['delete'],
                'tree' => $options['tree'],
                'relations' => $options['relations'],
            ]);

            // 更新从表-数据表字段信息
            foreach ($params['table_column'] as $item) {
                GenerateColumn::where('id', $item['id'])->update([
                    'column_comment' => $item['column_comment'] ?? '',
                    'is_required' => $item['is_required'] ?? 0,
                    'is_insert' => $item['is_insert'] ?? 0,
                    'is_update' => $item['is_update'] ?? 0,
                    'is_lists' => $item['is_lists'] ?? 0,
                    'is_query' => $item['is_query'] ?? 0,
                    'query_type' => $item['query_type'],
                    'view_type' => $item['view_type'],
                    'dict_type' => $item['dict_type'] ?? '',
                ]);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 删除表相关信息
     */
    public static function deleteTable($params)
    {
        DB::beginTransaction();
        try {
            $ids = is_array($params['id']) ? $params['id'] : [$params['id']];
            GenerateTable::query()->whereIn('id', $ids)->delete();
            GenerateColumn::query()->whereIn('table_id', $ids)->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 同步表字段
     */
    public static function syncColumn($params)
    {
        DB::beginTransaction();
        try {
            // table 信息
            $table = GenerateTable::query()->findOrFail($params['id']);
            // 删除旧字段
            GenerateColumn::whereIn('table_id', [$table['id']])->delete();
            // 获取当前数据表字段信息
            $column = self::getTableColumn($table['table_name']);
            // 创建新字段数据
            self::initTableColumn($column, $table['id']);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 生成代码
     */
    public static function generate($params)
    {
        try {
            // 获取数据表信息
            $ids = is_array($params['id']) ? $params['id'] : [$params['id']];
            $tables = GenerateTable::with(['tableColumn'])
                ->whereIn('id', $ids)
                ->get()->toArray();

            /**
             * @var GenerateService $generator
             */
            $generator = app()->make(GenerateService::class);
            $generator->delGenerateDirContent();
            $flag = array_unique(array_column($tables, 'table_name'));
            $flag = implode(',', $flag);
            $generator->setGenerateFlag(md5($flag . time()), false);

            // 循环生成
            foreach ($tables as $table) {
                $generator->generate($table);
            }

            $zipFile = '';
            // 生成压缩包
            if ($generator->getGenerateFlag()) {
                $generator->zipFile();
                $generator->delGenerateFlag();
                $zipFile = $generator->getDownloadUrl();
            }

            return ['file' => $zipFile];
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 预览
     */
    public static function preview($params)
    {
        try {
            // 获取数据表信息
            $ids = is_array($params['id']) ? $params['id'] : [$params['id']];
            $table = GenerateTable::with(['tableColumn'])
                ->whereIn('id', $ids)
                ->firstOrFail()->toArray();

            return (new GenerateService())->preview($table);
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 获取表字段信息
     */
    public static function getTableColumn($tableName)
    {
        $prefix = DB::getTablePrefix();
        $tableName = Str::replaceFirst($prefix, '', $tableName);
        return Schema::getColumns($tableName);
    }


    /**
     * @notes 初始化代码生成数据表信息
     */
    public static function initTable($tableData, $adminId)
    {
        return GenerateTable::query()->create([
            'table_name' => $tableData['name'],
            'table_comment' => $tableData['comment'],
            'template_type' => GeneratorEnum::TEMPLATE_TYPE_SINGLE,
            'generate_type' => GeneratorEnum::GENERATE_TYPE_ZIP,
            'module_name' => 'adminapi',
            'admin_id' => $adminId,
            // 菜单配置
            'menu' => [
                'pid' => 0, // 父级菜单id
                'type' => GeneratorEnum::GEN_SELF, // 构建方式 0-手动添加 1-自动构建
                'name' => $tableData['comment'], // 菜单名称
            ],
            // 删除配置
            'delete' => [
                'type' => GeneratorEnum::DELETE_TRUE, // 删除类型
                'name' => GeneratorEnum::DELETE_NAME, // 默认删除字段名
            ],
            // 关联配置
            'relations' => [],
            // 树形crud
            'tree' => []
        ]);
    }


    /**
     * @notes 初始化代码生成字段信息
     */
    public static function initTableColumn(array $columns, int $tableId): void
    {
        $insertColumns = collect($columns)
            ->map(function ($column) use ($tableId) {
                $defaultColumns = ['id', 'create_time', 'update_time', 'delete_time'];
                $isRequired = !$column['nullable'] && !$column['auto_increment']
                    && !in_array($column['name'], $defaultColumns);
                return [
                    'table_id' => $tableId,
                    'column_name' => $column['name'],
                    'column_comment' => $column['comment'] ?? '', // Handle potential null comment
                    'column_type' => self::getDbFieldType($column['type']),
                    'is_required' => $isRequired ? 1 : 0,
                    'is_pk' => $column['auto_increment'] ? 1 : 0,
                    'is_insert' => !in_array($column['name'], $defaultColumns) ? 1 : 0,
                    'is_update' => !in_array($column['name'], $defaultColumns) ? 1 : 0,
                    'is_lists' => !in_array($column['name'], $defaultColumns) ? 1 : 0,
                    'is_query' => !in_array($column['name'], $defaultColumns) ? 1 : 0,
                    'create_time' => time(),
                ];
            })
            ->toArray();
        GenerateColumn::insert($insertColumns);
    }


    /**
     * @notes 下载文件
     * @param $fileName
     * @return false|string
     * @author 段誉
     * @date 2022/6/24 9:51
     */
    public static function download(string $fileName)
    {
        $cacheFileName = Cache::get('curd_file_name' . $fileName);
        if (empty($cacheFileName)) {
            self::$error = '请重新生成代码';
            return false;
        }

        $path = storage_path() . '/generate/' . $fileName;
        if (!file_exists($path)) {
            self::$error = '下载失败';
            return false;
        }

        Cache::delete('curd_file_name' . $fileName);
        return $path;
    }


    /**
     * @notes 获取数据表字段类型
     * @param string $type
     * @return string
     * @author 段誉
     * @date 2022/6/15 10:11
     */
    public static function getDbFieldType(string $type): string
    {
        if (0 === strpos($type, 'set') || 0 === strpos($type, 'enum')) {
            $result = 'string';
        } elseif (preg_match('/(double|float|decimal|real|numeric)/is', $type)) {
            $result = 'float';
        } elseif (preg_match('/(int|serial|bit)/is', $type)) {
            $result = 'int';
        } elseif (preg_match('/bool/is', $type)) {
            $result = 'bool';
        } elseif (0 === strpos($type, 'timestamp')) {
            $result = 'timestamp';
        } elseif (0 === strpos($type, 'datetime')) {
            $result = 'datetime';
        } elseif (0 === strpos($type, 'date')) {
            $result = 'date';
        } else {
            $result = 'string';
        }
        return $result;
    }


    /**
     * @notes
     * @param $options
     * @param $tableComment
     * @return array
     * @author 段誉
     * @date 2022/12/13 18:23
     */
    public static function formatConfigByTableData($options)
    {
        // 菜单配置
        $menuConfig = $options['menu'] ?? [];
        // 删除配置
        $deleteConfig = $options['delete'] ?? [];
        // 关联配置
        $relationsConfig = $options['relations'] ?? [];
        // 树表crud配置
        $treeConfig = $options['tree'] ?? [];

        $relations = [];
        foreach ($relationsConfig as $relation) {
            $relations[] = [
                'name' => $relation['name'] ?? '',
                'model' => $relation['model'] ?? '',
                'type' => $relation['type'] ?? GeneratorEnum::RELATION_HAS_ONE,
                'local_key' => $relation['local_key'] ?? 'id',
                'foreign_key' => $relation['foreign_key'] ?? 'id',
            ];
        }

        $options['menu'] = [
            'pid' => intval($menuConfig['pid'] ?? 0),
            'type' => intval($menuConfig['type'] ?? GeneratorEnum::GEN_SELF),
            'name' => !empty($menuConfig['name']) ? $menuConfig['name'] : $options['table_comment'],
        ];
        $options['delete'] = [
            'type' => intval($deleteConfig['type'] ?? GeneratorEnum::DELETE_TRUE),
            'name' => !empty($deleteConfig['name']) ? $deleteConfig['name'] : GeneratorEnum::DELETE_NAME,
        ];
        $options['relations'] = $relations;
        $options['tree'] = [
            'tree_id' => $treeConfig['tree_id'] ?? "",
            'tree_pid' => $treeConfig['tree_pid'] ?? "",
            'tree_name' => $treeConfig['tree_name'] ?? '',
        ];

        return $options;
    }


    /**
     * @notes 获取所有模型
     * @param string $module
     * @return array
     * @author 段誉
     * @date 2022/12/14 11:04
     */
    public static function getAllModels($module = 'Common')
    {
        if (empty($module)) {
            return [];
        }
        $modulePath = app_path('/') . $module . '/Model/';
        if (!is_dir($modulePath)) {
            return [];
        }

        $modulefiles = glob($modulePath . '*');
        $targetFiles = [];
        foreach ($modulefiles as $file) {
            $fileBaseName = basename($file, '.php');
            if (is_dir($file)) {
                $file = glob($file . '/*');
                foreach ($file as $item) {
                    if (is_dir($item)) {
                        continue;
                    }
                    $targetFiles[] = sprintf(
                        "\\App\\" . $module . "\\Model\\%s\\%s",
                        $fileBaseName,
                        basename($item, '.php')
                    );
                }
            } else {
                if ($fileBaseName == 'BaseModel') {
                    continue;
                }
                $targetFiles[] = sprintf(
                    "\\App\\" . $module . "\\Model\\%s",
                    basename($file, '.php')
                );
            }
        }

        return $targetFiles;
    }

}
