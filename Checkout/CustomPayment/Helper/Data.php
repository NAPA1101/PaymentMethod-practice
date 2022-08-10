<?php
namespace Checkout\CustomPayment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data
{

    protected $configWriter;

    const XML_PATH_CUSTOMPAY = 'payment/';
    const XML_PATH_PRODUCT = 'payment/custompayment/multiselect_field';

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
   {
      $this->scopeConfig = $scopeConfig;
   }

    public function getConfigValue(string $field, int $storeId = null) : int
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGeneralConfig(string $code, int $storeId = null) : int
    {
        return $this->getConfigValue(
            self::XML_PATH_CUSTOMPAY .'custompayment/'. $code, $storeId
        );
    }

    public function getCategoryProduct() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::XML_PATH_PRODUCT, $storeScope);
    }
}
