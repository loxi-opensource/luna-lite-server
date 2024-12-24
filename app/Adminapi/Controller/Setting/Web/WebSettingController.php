<?php

namespace App\Adminapi\Controller\Setting\Web;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Setting\Web\WebSettingLogic;
use App\Adminapi\Validate\Setting\WebSettingValidate;

/**
 * 网站设置
 */
class WebSettingController extends BaseAdminController
{

    /**
     * @notes 获取网站信息
     */
    public function getWebsite()
    {
        $result = WebSettingLogic::getWebsiteInfo();
        return $this->data($result);
    }


    /**
     * @notes 设置网站信息
     */
    public function setWebsite()
    {
        $params = (new WebSettingValidate())->post()->goCheck('website');
        WebSettingLogic::setWebsiteInfo($params);
        return $this->success('设置成功', [], 1, 1);
    }


    /**
     * @notes 获取备案信息
     */
    public function getCopyright()
    {
        $result = WebSettingLogic::getCopyright();
        return $this->data($result);
    }


    /**
     * @notes 设置备案信息
     */
    public function setCopyright()
    {
        $params = $this->request->post();
        $result = WebSettingLogic::setCopyright($params);
        if (false === $result) {
            return $this->fail(WebSettingLogic::getError() ?: '操作失败');
        }
        return $this->success('设置成功', [], 1, 1);
    }


    /**
     * @notes 设置政策协议
     */
    public function setAgreement()
    {
        $params = $this->request->post();
        WebSettingLogic::setAgreement($params);
        return $this->success('设置成功', [], 1, 1);
    }


    /**
     * @notes 获取政策协议
     */
    public function getAgreement()
    {
        $result = WebSettingLogic::getAgreement();
        return $this->data($result);
    }

    /**
     * @notes 获取站点统计配置
     */
    public function getSiteStatistics()
    {
        $result = WebSettingLogic::getSiteStatistics();
        return $this->data($result);
    }

    /**
     * @notes 获取站点统计配置
     */
    public function setSiteStatistics()
    {
        $params = (new WebSettingValidate())->post()->goCheck('siteStatistics');
        WebSettingLogic::setSiteStatistics($params);
        return $this->success('设置成功', [], 1, 1);
    }
}
