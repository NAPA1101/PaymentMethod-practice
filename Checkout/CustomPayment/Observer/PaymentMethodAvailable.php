<?php
namespace Checkout\CustomPayment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\ObjectManagerInterface;
use Checkout\CustomPayment\Helper\Data;


class PaymentMethodAvailable implements ObserverInterface
{
    /**
     * @var CustomerSession
     */
    protected $_customerSession;

    /**
     * @param CustomerSession $customerSession
     * @param CheckoutSession $checkoutSession
     * @param ObjectManagerInterface $objectmanager
     * @param Data $helper
     */

    public function __construct(
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        ObjectManagerInterface $objectmanager,
        Data $helper
    ) {
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->_objectManager = $objectmanager;
        $this->_helper = $helper;
    }

    public function execute(Observer $observer)
    {
        $payment_method_code = $observer->getEvent()->getMethodInstance()->getCode();
        $shippingMethod = $this->_checkoutSession->getQuote()->getShippingAddress()->getShippingMethod();
        $categoryProduct = explode(',', $this->_helper->getCategoryProduct());

        if ($payment_method_code == 'custompayment' && $shippingMethod !== 'customshipping_customshipping')
        {
            $result = $observer->getEvent()->getResult();
            $result->setData('is_available', false);
        } elseif (in_array($categoryProduct, $this->getCategoryItemCart()) == false 
                  && $shippingMethod == 'customshipping_customshipping' && $payment_method_code == 'custompayment'){
            $result = $observer->getEvent()->getResult();
            $result->setData('is_available', false);
        } else {
            $result = $observer->getEvent()->getResult();
            $result->setData('is_available', true);
        }
        
    }

    public function getCategoryItemCart()
    {
        /** @var $item \Magento\Quote\Model\Quote\Item */
        $items = $this->_checkoutSession->getQuote()->getAllItems();
        foreach ($items as $item) {
            $productid = $item->getProductId();
            $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($productid);
            $categoriesIds[] = $product->getCategoryIds();
        }
        return $categoriesIds;     
    }
}