<?php

namespace Mpx\Marketplace\Controller;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Webkul\Marketplace\Helper\Data;
use Webkul\Marketplace\Model\SellerFactory;

/**
 * Class Router
 */
class CustomPolicyPageRouter implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var SellerFactory
     */
    protected $sellerFactory;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param ActionFactory $actionFactory
     * @param Data $helper
     * @param SellerFactory $sellerFactory
     * @param Session $customerSession
     */
    public function __construct(
        ActionFactory $actionFactory,
        Data $helper,
        SellerFactory $sellerFactory,
        Session $customerSession
    ) {
        $this->actionFactory = $actionFactory;
        $this->helper = $helper;
        $this->sellerFactory = $sellerFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * Retrieve customer session object.
     *
     * @return Session
     */
    protected function getSession(): Session
    {
        return $this->customerSession;
    }

    /**
     * Change policy Page Router
     *
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request): ?ActionInterface
    {
        $sellerId = $this->getSession()->getCustomerId();
        $storeId = $this->helper->getCurrentStoreId();
        $sellerCollection = $this->sellerFactory->create()
            ->getCollection()->addFieldToFilter('seller_id', $sellerId)
                             ->addFieldToFilter('store_id', $storeId);

        $shopUrl = $sellerCollection->getFirstItem()->getShopUrl();
        $sellerPolicyUrl = 'marketplace/seller/tokuteitorihiki/shop/'.$shopUrl;
        $identifier = trim($request->getPathInfo(), '/');
        if (strpos($identifier, $sellerPolicyUrl) !== false) {
            $request->setModuleName('marketplace');
            $request->setControllerName('seller');
            $request->setActionName('policy');
            $request->setParams([
                'shop' => $shopUrl
            ]);
            return $this->actionFactory->create(Forward::class, ['request' => $request]);
        }
        return null;
    }
}
