<?php

class FreddyEnv
{
    protected $filePath = '';

    /**
     * 读取环境变量定义文件
     * @access public
     * @param string $file 环境变量定义文件
     * @return void
     */
    public function load($file)
    {
        $this->filePath = $file;
    }

    public function makeEnv($file)
    {
        if (!file_exists($file)) {
            try {
                touch($file);
            } catch (\Exception $e) {
                return;
            }
        }
    }

    /**
     * Notes: 写入Env文件
     * @param $envFilePath
     * @param array $databaseEnv
     */
    public function putEnv($envFilePath, array $databaseEnv)
    {
        $applyDbEnv = [
            '{DB_HOST}' => $databaseEnv['host'],
            '{DB_DATABASE}' => $databaseEnv['name'],
            '{DB_USERNAME}' => $databaseEnv['user'],
            '{DB_PASSWORD}' => $databaseEnv['password'],
            '{DB_PORT}' => $databaseEnv['port'],
            '{DB_PREFIX}' => $databaseEnv['prefix'],
            '{REDIS_HOST}' => $databaseEnv['redis_host'],
            '{REDIS_PASSWORD}' => $databaseEnv['redis_password'],
            '{REDIS_PORT}' => $databaseEnv['redis_port'],
        ];
        global $uniqueSalt;
        $toReplace = array_merge($applyDbEnv, ['{PROJECT_UNIQUE_IDENTIFICATION}' => $uniqueSalt]);
        $exampleEnv = file_get_contents($this->filePath);
        foreach ($toReplace as $key => $value) {
            $exampleEnv = str_replace($key, $value, $exampleEnv);
        }
        file_put_contents($envFilePath, $exampleEnv);
    }
}
