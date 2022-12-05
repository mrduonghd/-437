<?php

namespace Mpx\Marketplace\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\DB\Helper as FrameworkDbHelper;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\GoogleOptimizer\Model\Code as ModelCode;
use Webkul\Marketplace\Helper\Data as HelperData;
use Magento\Store\Model\StoreManager;

/**
 * Class Product Create
 */
class Create extends \Webkul\Marketplace\Block\Product\Create
{
    const SKU_PREFIX_LENGTH = 4;

    /**
     * @var StoreManager
     */
    protected $storeManager;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param Product $product
     * @param Category $category
     * @param ModelCode $modelCode
     * @param HelperData $helperData
     * @param ProductRepositoryInterface $productRepository
     * @param CollectionFactory $categoryCollectionFactory
     * @param FrameworkDbHelper $frameworkDbHelper
     * @param CategoryHelper $categoryHelper
     * @param DataPersistorInterface $dataPersistor
     * @param StoreManager $storeManager
     * @param SerializerInterface $serializer
     * @param array $data
     * @param \Magento\Cms\Helper\Wysiwyg\Images|null $wysiwygImages
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        Product $product,
        Category $category,
        ModelCode $modelCode,
        HelperData $helperData,
        ProductRepositoryInterface $productRepository,
        CollectionFactory $categoryCollectionFactory,
        FrameworkDbHelper $frameworkDbHelper,
        CategoryHelper $categoryHelper,
        DataPersistorInterface $dataPersistor,
        StoreManager $storeManager,
        SerializerInterface $serializer,
        array $data = [],
        \Magento\Cms\Helper\Wysiwyg\Images $wysiwygImages = null
    ) {
        $this->storeManager = $storeManager;
        parent::__construct(
            $context,
            $product,
            $category,
            $modelCode,
            $helperData,
            $productRepository,
            $categoryCollectionFactory,
            $frameworkDbHelper,
            $categoryHelper,
            $dataPersistor,
            $serializer,
            $data,
            $wysiwygImages
        );
    }

    /**
     * Get Sku Format
     *
     * @param string $sku
     * @return false|string
     */
    public function getUnformattedSku($sku)
    {
        return substr($sku, self::SKU_PREFIX_LENGTH);
    }

    /**
     * Get Default Category Id
     *
     * @return int|void
     */
    public function getDefaultCategoryId()
    {
        try {
            return (int)$this->storeManager->getStore()->getRootCategoryId();
        } catch (\Exception $exception) {
            $this->messageManager->addError("Can't get default category");
        }
    }
}
