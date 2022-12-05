<?php

namespace Mpx\Sales\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * Get Url
     *
     * @param string $shopPageUrl
     * @return string
     */
    public function getUrl(string $shopPageUrl): string
    {
        try {
            $store = $this->storeManager->getStore();
            if ($store) {
                $url =  $store->getBaseUrl();
                return $url."marketplace/seller/profile/shop/".$shopPageUrl;
            }
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
            return "";
        }
        return "";
    }
}
