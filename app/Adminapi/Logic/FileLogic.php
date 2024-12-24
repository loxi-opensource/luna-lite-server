<?php

namespace App\Adminapi\Logic;

use App\Common\Logic\BaseLogic;
use App\Common\Model\File\File;
use App\Common\Model\File\FileCate;
use App\Common\Service\ConfigService;
use App\Common\Service\Storage\Driver as StorageDriver;

/**
 * 文件逻辑层
 */
class FileLogic extends BaseLogic
{
    /**
     * @notes 移动文件
     */
    public static function move($params)
    {
        File::query()->whereIn('id', $params['ids'])
            ->update([
                'cid' => $params['cid'],
                'update_time' => time()
            ]);
    }

    /**
     * @notes 重命名文件
     */
    public static function rename($params)
    {
        File::query()->where('id', $params['id'])
            ->update([
                'name' => $params['name'],
                'update_time' => time()
            ]);
    }

    /**
     * @notes 批量删除文件
     */
    public static function delete($params)
    {
        $result = File::query()->whereIn('id', $params['ids'])->get();
        $driver = new StorageDriver([
            'default' => ConfigService::get('storage', 'default', 'local'),
            'engine' => ConfigService::get('storage') ?? ['local' => []],
        ]);
        foreach ($result as $item) {
            $driver->delete($item['uri']);
        }
        File::destroy($params['ids']);
    }

    /**
     * @notes 添加文件分类
     */
    public static function addCate($params)
    {
        FileCate::create([
            'type' => $params['type'],
            'pid' => $params['pid'],
            'name' => $params['name']
        ]);
    }

    /**
     * @notes 编辑文件分类
     */
    public static function editCate($params)
    {
        FileCate::query()->where('id', $params['id'])
            ->update([
                'name' => $params['name'],
                'update_time' => time()
            ]);
    }

    /**
     * @notes 删除文件分类
     */
    public static function delCate($params)
    {
        $fileModel = new File();
        $cateModel = new FileCate();

        $cateIds = self::getCateIds($params['id']);
        array_push($cateIds, $params['id']);

        // 删除分类及子分类
        $cateModel->whereIn('id', $cateIds)->update(['delete_time' => time()]);

        // 删除文件
        $fileIds = $fileModel->whereIn('cid', $cateIds)->pluck('id')->toArray();

        if (!empty($fileIds)) {
            self::delete(['ids' => $fileIds]);
        }
    }


    /**
     * @notes 获取所有分类id
     */
    public static function getCateIds($parentId, array $cateArr = []): array
    {
        $childIds = FileCate::query()->where(['pid' => $parentId])->pluck('id')->toArray();

        if (empty($childIds)) {
            return [];
        } else {
            $allChildIds = $childIds;
            foreach ($childIds as $childId) {
                $allChildIds = array_merge($allChildIds, static::getCateIds($childId, $cateArr));
            }
            return $allChildIds;
        }
    }

}
