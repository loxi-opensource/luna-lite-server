<?php

namespace App\Common\Service\Storage\Engine;

use OSS\Core\OssException;
use OSS\OssClient;

/**
 * 阿里云存储引擎 (OSS)
 */
class Aliyun extends Server
{
    private $config;

    /**
     * 构造方法
     * Aliyun constructor.
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct();
        $this->config = $config;
    }

    /**
     * 执行上传
     * @param $save_dir (保存路径)
     * @return bool|mixed
     */
    public function upload($save_dir)
    {
        try {
            $ossClient = new OssClient(
                $this->config['access_key'],
                $this->config['secret_key'],
                $this->config['domain'],
                true
            );
            $ossClient->uploadFile(
                $this->config['bucket'],
                $save_dir . '/' . $this->fileName,
                $this->getRealPath()
            );
        } catch (OssException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * 同步目录
     */
    public function syncDir($localDir, $targetDir)
    {
        if (DIRECTORY_SEPARATOR == '\\') {
            $this->error = '阿里云OSS目录同步不支持反斜杠目录分隔符，请在Linux环境下运行';
            return false;
        }

        // 阿里云OSS不需要以'/'开头和结尾
        $targetDir = trim($targetDir, '/');

        try {
            $ossClient = new OssClient(
                $this->config['access_key'],
                $this->config['secret_key'],
                $this->config['domain'],
                true
            );
            $res = $ossClient->uploadDir(
                $this->config['bucket'],
                $targetDir,
                $localDir,
                '.|..|.svn|.git',
                true
            );
            return $res;
            // 返回结果示例
            //array:2 [
            //  "succeededList" => array:3 [
            //    0 => "dir2/bar.txt"
            //    1 => "dir1/foo.txt"
            //    2 => "ad01.jpg"
            //  ]
            //  "failedList" => []

            //array:2 [
            //  "succeededList" => []
            //  "failedList" => array:3 [
            //    "/test2/dir2/bar.txt" => ""/test2/dir2/bar.txt" object name is invalid"
            //    "/test2/dir1/foo.txt" => ""/test2/dir1/foo.txt" object name is invalid"
            //    "/test2/ad01.jpg" => ""/test2/ad01.jpg" object name is invalid"
            //  ]
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }

        $this->error = '错误未定义：目录同步结果为空';
        return false;
    }

    /**
     * Notes: 抓取远程资源
     * @param $url
     * @param null $key
     * @return mixed|void
     * @author 张无忌(2021/3/2 14:36)
     */
    public function fetch($url, $key = null)
    {
        try {
            $ossClient = new OssClient(
                $this->config['access_key'],
                $this->config['secret_key'],
                $this->config['domain'],
                true
            );

            $content = file_get_contents($url);
            $ossClient->putObject(
                $this->config['bucket'],
                $key,
                $content
            );
        } catch (OssException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * 删除文件
     * @param $fileName
     * @return bool|mixed
     */
    public function delete($fileName)
    {
        try {
            $ossClient = new OssClient(
                $this->config['access_key'],
                $this->config['secret_key'],
                $this->config['domain'],
                true
            );
            $ossClient->deleteObject($this->config['bucket'], $fileName);
        } catch (OssException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * 返回文件路径
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

}
