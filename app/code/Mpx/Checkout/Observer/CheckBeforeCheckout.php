<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Checkout
 * @author    Mpx
 */

namespace Mpx\Checkout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Mpx\Checkout\Helper\Data as MpxData;
use Magento\Customer\Model\Session;

class CheckBeforeCheckout implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var MpxData
     */
    protected $_helper;

    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @param UrlInterface $url
     * @param MpxData $_helper
     * @param ManagerInterface $messageManager
     * @param Session $customerSession
     */
    public function __construct(
        UrlInterface     $url,
        MpxData          $_helper,
        ManagerInterface $messageManager,
        Session          $customerSession
    ) {
        $this->url = $url;
        $this->_helper = $_helper;
        $this->messageManager = $messageManager;
        $this->customerSession = $customerSession;
    }

    /**
     * Check Login After Check Multi Seller In Page Checkout
     *
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        if ($this->customerSession->isLoggedIn()) {
            try {
                $limitSellerOnCheckout = $this->_helper->CountSellerInCart();
                if ($limitSellerOnCheckout > 1) {
                    $checkoutUrl = $this->url->getUrl('checkout/cart');
                    $observer->getControllerAction()
                        ->getResponse()
                        ->setRedirect($checkoutUrl);
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage('Error cannot check seller cart');
            }
        } else {
            $loginUrl = $this->url->getUrl('customer/account/login');
            $this->customerSession->setBeforeAuthUrl($this->url->getUrl('checkout'));
            $observer->getControllerAction()
                ->getResponse()
                ->setRedirect($loginUrl);
        }
        return $this;
    }
}
