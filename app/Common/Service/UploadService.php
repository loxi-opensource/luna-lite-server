<?php

namespace App\Common\Service;

use App\Common\Enum\FileEnum;
use App\Common\Model\File\File;
use App\Common\Service\Storage\Driver as StorageDriver;
use Exception;

class UploadService
{
    /**
     * @notes 上传图片
     * @param $cid
     * @param int $user_id
     * @param string $saveDir
     * @return array
     */
    public static function image($cid, int $sourceId = 0, int $source = FileEnum::SOURCE_ADMIN, string $saveDir = 'uploads/images')
    {
        try {
            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine' => ConfigService::get('storage') ?? ['local' => []],
            ];

            // 2、执行文件上传
            $driver = new StorageDriver($config);
            $driver->setUploadFile('file');
            $fileName = $driver->getFileName();
            $fileInfo = $driver->getFileInfo();

            // 上传文件
            $saveDir = self::getUploadUrl($saveDir);
            if (!$driver->upload($saveDir)) {
                throw new Exception($driver->getError());
            }

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $name = substr($fileInfo['name'], 0, 123);
                $nameEnd = substr($fileInfo['name'], strlen($fileInfo['name']) - 5, strlen($fileInfo['name']));
                $fileInfo['name'] = $name . $nameEnd;
            }

            // 4、写入数据库中
            $file = File::create([
                'cid' => $cid,
                'type' => FileEnum::IMAGE_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $saveDir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $sourceId,
                'create_time' => time(),
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri']
            ];

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    /**
     * @notes 视频上传
     * @param $cid
     * @param int $user_id
     * @param string $saveDir
     * @return array
     */
    public static function video($cid, int $sourceId = 0, int $source = FileEnum::SOURCE_ADMIN, string $saveDir = 'uploads/video')
    {
        try {
            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine' => ConfigService::get('storage') ?? ['local' => []],
            ];

            // 2、执行文件上传
            $driver = new StorageDriver($config);
            $driver->setUploadFile('file');
            $fileName = $driver->getFileName();
            $fileInfo = $driver->getFileInfo();

            // 上传文件
            $saveDir = self::getUploadUrl($saveDir);
            if (!$driver->upload($saveDir)) {
                throw new Exception($driver->getError());
            }

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $name = substr($fileInfo['name'], 0, 123);
                $nameEnd = substr($fileInfo['name'], strlen($fileInfo['name']) - 5, strlen($fileInfo['name']));
                $fileInfo['name'] = $name . $nameEnd;
            }

            // 4、写入数据库中
            $file = File::create([
                'cid' => $cid,
                'type' => FileEnum::VIDEO_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $saveDir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $sourceId,
                'create_time' => time(),
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri']
            ];

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @notes 上传文件
     * @param $cid
     * @param int $sourceId
     * @param int $source
     * @param string $saveDir
     * @return array
     */
    public static function file($cid, int $sourceId = 0, int $source = FileEnum::SOURCE_ADMIN, string $saveDir = 'uploads/file')
    {
        try {
            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine' => ConfigService::get('storage') ?? ['local' => []],
            ];

            // 2、执行文件上传
            $driver = new StorageDriver($config);
            $driver->setUploadFile('file');
            $fileName = $driver->getFileName();
            $fileInfo = $driver->getFileInfo();

            // 上传文件
            $saveDir = self::getUploadUrl($saveDir);
            if (!$driver->upload($saveDir)) {
                throw new Exception($driver->getError());
            }

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $name = substr($fileInfo['name'], 0, 123);
                $nameEnd = substr($fileInfo['name'], strlen($fileInfo['name']) - 5, strlen($fileInfo['name']));
                $fileInfo['name'] = $name . $nameEnd;
            }

            // 4、写入数据库中
            $file = File::create([
                'cid' => $cid,
                'type' => FileEnum::FILE_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $saveDir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $sourceId,
                'create_time' => time(),
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri']
            ];

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @notes 上传地址
     * @param $saveDir
     * @return string
     */
    private static function getUploadUrl($saveDir): string
    {
        return $saveDir . '/' . date('Ymd');
    }
}
