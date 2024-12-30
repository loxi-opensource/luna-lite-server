<?php

namespace App\Common\Service\Aliyun;

use Illuminate\Support\Facades\Cache;

class ImageCropService
{
    private $imageUrl;
    private $faceList;
    private $width;
    private $height;

    public function __construct($imageUrl)
    {
        $this->imageUrl = $imageUrl;
        list($this->width, $this->height) = $this->getImageInfo();
    }

    public function getImageWidth()
    {
        return $this->width;
    }

    public function getImageHeight()
    {
        return $this->height;
    }

    private function getImageInfo()
    {
        $imageUrl = $this->imageUrl;
        $key = 'oss_image_info:' . md5($imageUrl);
        return Cache::rememberForever($key, function () use ($imageUrl) {
            // 发起 GET 请求获取图片信息
            $infoUrl = $imageUrl . "?x-oss-process=image/info";
            $response = file_get_contents($infoUrl);

            if ($response === false) {
                throw new \Exception("Unable to fetch image info.");
            }

            // 解析 JSON 响应
            $info = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON response.");
            }

            return [
                (int)$info['ImageWidth']['value'],
                (int)$info['ImageHeight']['value'],
            ];
        });
    }

    // 按x,y,w,h比例裁剪图片
    public function cropByRatio($xRatio, $yRatio, $wRatio, $hRatio)
    {
        // 计算绝对坐标
        $left = $xRatio * $this->width;
        $top = $yRatio * $this->height;
        $cropWidth = $wRatio * $this->width;
        $cropHeight = $hRatio * $this->height;

        // 生成裁剪 URL
        $cropUrl = sprintf(
            "%s?x-oss-process=image/crop,x_%d,y_%d,w_%d,h_%d",
            $this->imageUrl,
            round($left),
            round($top),
            round($cropWidth),
            round($cropHeight)
        );

        return $cropUrl;
    }


}

