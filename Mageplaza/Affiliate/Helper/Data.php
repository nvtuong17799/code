<?php

namespace Mageplaza\Affiliate\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    const XML_PATH_GIFTCARD = 'affiliate/';

    const CHARS = 'abcdefghijklmnopqrstuvwxyz0123456789';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GIFTCARD .'general/'. $code, $storeId);
    }

    public function getRuleConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GIFTCARD .'rule/'. $code, $storeId);
    }

    public function getChars(){
        return self::CHARS;
    }

}
