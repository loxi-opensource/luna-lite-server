<?php

namespace App\Common\Service\Aliyun;

use AlibabaCloud\SDK\Green\V20220302\Green;
use AlibabaCloud\SDK\Green\V20220302\Models\ImageModerationRequest;
use AlibabaCloud\Tea\Utils\Utils;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use App\Adminapi\Logic\Setting\ImageCheckSettingLogic;
use Darabonba\OpenApi\Models\Config;
use Illuminate\Support\Facades\Log;

/**
 * 图片审核增强版API
 * 文档地址：https://help.aliyun.com/document_detail/467829.html
 */
class ImageGreenCheckService
{
    private $client;

    private $systemConfig;

    public function __construct($systemConfig = null)
    {
        if (empty($systemConfig)) {
            $systemConfig = (new ImageCheckSettingLogic())->getConfig();
        }
        $this->systemConfig = $systemConfig;
        $configData = [
            "accessKeyId" => $systemConfig['access_key'],
            "accessKeySecret" => $systemConfig['secret_key'],
            "endpoint" => $systemConfig['endpoint'],
            "regionId" => $systemConfig['region_id'],
        ];

        $config = new Config($configData);
        $this->client = new Green($config);
    }

    public function checkImage($url)
    {
        if (!$this->systemConfig['audit_open']) {
            return true;
        }

        $request = new ImageModerationRequest();
        $request->service = "baselineCheck";
        $serviceParameters = array(
            'imageUrl' => $url,
            'dataId' => md5($url)
        );
        $request->serviceParameters = json_encode($serviceParameters);

        // 创建RuntimeObject实例并设置运行参数。
        $runtime = new RuntimeOptions([]);
        $runtime->readTimeout = 10000;
        $runtime->connectTimeout = 10000;

        $response = $this->client->imageModerationWithOptions($request, $runtime);
        if (Utils::equalNumber(500, $response->statusCode) || !Utils::equalNumber(200, $response->body->code)) {
            Log::error("图片安全检测服务异常", compact('response', 'request'));
            $e = new \Exception("图片安全检测服务异常");
            throw $e;
        }

        $resp = $response->body->toMap();

        if (isset($resp['Data']['Result'][0]['Label']) && $resp['Data']['Result'][0]['Label'] == 'nonLabel') {
            return true;
        };
        if (count($resp['Data']['Result'])) {
            $riskLabels = array_filter($resp['Data']['Result'], function ($item) {
                return $item['Confidence'] > 85;
            });
            if (!count($riskLabels)) {
                Log::debug("图片检测服务-风险度不够已放行", compact('resp', 'request', 'riskLabels'));
                return true;
            }
            if (count($riskLabels)) {
                Log::info("图片检测服务-风险度过高已拦截", compact('resp', 'request', 'riskLabels'));
                return false;
            }
        }

        Log::error("图片检测服务-响应异常直接放行", compact('resp', 'request'));
        return true;
    }
}
