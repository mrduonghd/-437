<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Marketplace
 * @author    Mpx
 */

namespace Mpx\Marketplace\Controller\Account;

/**
 * Webkul Marketplace Account RewriteUrlPost Controller.
 */
class RewriteUrlPost extends \Webkul\Marketplace\Controller\Account\RewriteUrlPost
{
    /**
     * Seller's Custom URL Post action.
     *
     * @return \Magento\Framework\Controller\Result\RedirectFactory
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($this->getRequest()->isPost()) {
            try {
                if (!$this->_formKeyValidator->validate($this->getRequest())) {
                    return $this->resultRedirectFactory->create()->setPath(
                        '*/*/editProfile',
                        ['_secure' => $this->getRequest()->isSecure()]
                    );
                }
                $fields = $this->getRequest()->getParams();
                $sellerId = $this->_getSession()->getCustomerId();
                $collection = $this->sellerModel->create()
                    ->getCollection()
                    ->addFieldToFilter('seller_id', $sellerId);
                foreach ($collection as $value) {
                    $profileurl = $value->getShopUrl();
                }

                $getCurrentStoreId = $this->helper->getCurrentStoreId();

                if (isset($fields['profile_request_url']) && $fields['profile_request_url']) {
                    $sourceUrl = 'marketplace/seller/profile/shop/'.$profileurl;
                    /*
                    * Check if already rexist in url rewrite model
                    */
                    $urlId = 0;
                    $profileRequestUrl = '';
                    $urlCollectionData = $this->urlRewriteFactory->create()
                        ->getCollection()
                        ->addFieldToFilter('target_path', $sourceUrl)
                        ->addFieldToFilter('store_id', $getCurrentStoreId);
                    foreach ($urlCollectionData as $value) {
                        $urlId = $value->getId();
                        $profileRequestUrl = $value->getRequestPath();
                    }
                    if ($profileRequestUrl != $fields['profile_request_url']) {
                        $idPath = rand(1, 100000);
                        $this->urlRewriteFactory->create()
                            ->load($urlId)
                            ->setStoreId($getCurrentStoreId)
                            ->setIsSystem(0)
                            ->setIdPath($idPath)
                            ->setTargetPath($sourceUrl)
                            ->setRequestPath($fields['profile_request_url'])
                            ->save();
                    }
                }
                if (isset($fields['collection_request_url']) && $fields['collection_request_url']) {
                    $sourceUrl = 'marketplace/seller/collection/shop/'.$profileurl;
                    /*
                    * Check if already rexist in url rewrite model
                    */
                    $urlId = 0;
                    $collectionRequestUrl = '';
                    $urlCollectionData = $this->urlRewriteFactory->create()
                        ->getCollection()
                        ->addFieldToFilter('target_path', $sourceUrl)
                        ->addFieldToFilter('store_id', $getCurrentStoreId);
                    foreach ($urlCollectionData as $value) {
                        $urlId = $value->getId();
                        $collectionRequestUrl = $value->getRequestPath();
                    }
                    if ($collectionRequestUrl != $fields['collection_request_url']) {
                        $idPath = rand(1, 100000);
                        $this->urlRewriteFactory->create()
                            ->load($urlId)
                            ->setStoreId($getCurrentStoreId)
                            ->setIsSystem(0)
                            ->setIdPath($idPath)
                            ->setTargetPath($sourceUrl)
                            ->setRequestPath($fields['collection_request_url'])
                            ->save();
                    }
                }
                if (isset($fields['review_request_url']) && $fields['review_request_url']) {
                    $sourceUrl = 'marketplace/seller/feedback/shop/'.$profileurl;
                    /*
                    * Check if already rexist in url rewrite model
                    */
                    $urlId = 0;
                    $reviewRequestUrl = '';
                    $urlCollectionData = $this->urlRewriteFactory->create()
                        ->getCollection()
                        ->addFieldToFilter('target_path', $sourceUrl)
                        ->addFieldToFilter('store_id', $getCurrentStoreId);
                    foreach ($urlCollectionData as $value) {
                        $urlId = $value->getId();
                        $reviewRequestUrl = $value->getRequestPath();
                    }
                    if ($reviewRequestUrl != $fields['review_request_url']) {
                        $idPath = rand(1, 100000);
                        $this->urlRewriteFactory->create()
                            ->load($urlId)
                            ->setStoreId($getCurrentStoreId)
                            ->setIsSystem(0)
                            ->setIdPath($idPath)
                            ->setTargetPath($sourceUrl)
                            ->setRequestPath($fields['review_request_url'])
                            ->save();
                    }
                }
                if (isset($fields['location_request_url']) && $fields['location_request_url']) {
                    $sourceUrl = 'marketplace/seller/location/shop/'.$profileurl;
                    /*
                    * Check if already rexist in url rewrite model
                    */
                    $urlId = 0;
                    $locationRequestUrl = '';
                    $urlCollectionData = $this->urlRewriteFactory->create()
                        ->getCollection()
                        ->addFieldToFilter('target_path', $sourceUrl)
                        ->addFieldToFilter('store_id', $getCurrentStoreId);
                    foreach ($urlCollectionData as $value) {
                        $urlId = $value->getId();
                        $locationRequestUrl = $value->getRequestPath();
                    }
                    if ($locationRequestUrl != $fields['location_request_url']) {
                        $idPath = rand(1, 100000);
                        $this->urlRewriteFactory->create()
                            ->load($urlId)
                            ->setStoreId($getCurrentStoreId)
                            ->setIsSystem(0)
                            ->setIdPath($idPath)
                            ->setTargetPath($sourceUrl)
                            ->setRequestPath($fields['location_request_url'])
                            ->save();
                    }
                }
                if (isset($fields['policy_request_url']) && $fields['policy_request_url']) {
                    $sourceUrl = 'marketplace/seller/tokuteitorihiki/shop/'.$profileurl;
                    /*
                    * Check if already rexist in url rewrite model
                    */
                    $urlId = 0;
                    $policyRequestUrl = '';
                    $urlCollectionData = $this->urlRewriteFactory->create()
                        ->getCollection()
                        ->addFieldToFilter('target_path', $sourceUrl)
                        ->addFieldToFilter('store_id', $getCurrentStoreId);
                    foreach ($urlCollectionData as $value) {
                        $urlId = $value->getId();
                        $policyRequestUrl = $value->getRequestPath();
                    }
                    if ($policyRequestUrl != $fields['policy_request_url']) {
                        $idPath = rand(1, 100000);
                        $this->urlRewriteFactory->create()
                            ->load($urlId)
                            ->setStoreId($getCurrentStoreId)
                            ->setIsSystem(0)
                            ->setIdPath($idPath)
                            ->setTargetPath($sourceUrl)
                            ->setRequestPath($fields['policy_request_url'])
                            ->save();
                    }
                }
                // clear cache
                $this->helper->clearCache();
                $this->messageManager->addSuccess(__('The URL Rewrite has been saved.'));

                return $this->resultRedirectFactory->create()->setPath(
                    '*/*/editProfile',
                    ['_secure' => $this->getRequest()->isSecure()]
                );
            } catch (\Exception $e) {
                $this->helper->logDataInLogger(
                    "Controller_Account_RewriteUrlPost execute : ".$e->getMessage()
                );
                $this->messageManager->addError($e->getMessage());

                return $this->resultRedirectFactory->create()->setPath(
                    '*/*/editProfile',
                    ['_secure' => $this->getRequest()->isSecure()]
                );
            }
        } else {
            return $this->resultRedirectFactory->create()->setPath(
                '*/*/editProfile',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }
}
