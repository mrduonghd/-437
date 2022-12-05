<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Marketplace
 * @author    Mpx
 */

namespace Mpx\Marketplace\Observer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Session\SessionManager;
use Magento\Quote\Model\QuoteRepository;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\AddressFactory;
use Webkul\Marketplace\Helper\Data as MarketplaceHelper;
use Webkul\Marketplace\Helper\Email as MpEmailHelper;
use Webkul\Marketplace\Helper\Notification as NotificationHelper;
use Webkul\Marketplace\Helper\Orders as OrdersHelper;
use Webkul\Marketplace\Model\OrderPendingMailsFactory;
use Webkul\Marketplace\Model\OrdersFactory;
use Webkul\Marketplace\Model\ProductFactory;
use Webkul\Marketplace\Model\SaleperpartnerFactory;
use Webkul\Marketplace\Model\SaleslistFactory;
use Webkul\Marketplace\Model\SellerFactory;

/**
 * Mpx Marketplace SalesOrderPlaceAfterObserver Observer Model.
 */
class SalesOrderPlaceAfterObserver extends \Webkul\Marketplace\Observer\SalesOrderPlaceAfterObserver
{
    /**
     * @var eventManager
     */
    protected $_eventManager;

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * [$_coreSession description].
     *
     * @var SessionManager
     */
    protected $_coreSession;

    /**
     * @var QuoteRepository
     */
    protected $_quoteRepository;

    /**
     * @var OrderRepositoryInterface
     */
    protected $_orderRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $_customerRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * @var MarketplaceHelper
     */
    protected $_marketplaceHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var AddressFactory
     */
    protected $orderAddressFactory;

    /**
     * @var SaleslistFactory
     */
    protected $saleslistFactory;

    /**
     * @var CountryFactory
     */
    protected $countryModel;

    /**
     * @var MpEmailHelper
     */
    protected $mpEmailHelper;

    /**
     * @var OrdersHelper
     */
    protected $ordersHelper;

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var OrdersFactory
     */
    protected $ordersFactory;

    /**
     * @var OrderPendingMailsFactory
     */
    protected $orderPendingMailsFactory;

    /**
     * @var NotificationHelper
     */
    protected $notificationHelper;

    /**
     * @var SaleperpartnerFactory
     */
    protected $saleperpartnerFactory;

    /**
     * @var SellerFactory
     */
    protected $sellerFactory;

    /**
     * @param \Magento\Framework\Event\Manager $eventManager
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param SessionManager $coreSession
     * @param QuoteRepository $quoteRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param ProductRepositoryInterface $productRepository
     * @param MarketplaceHelper $marketplaceHelper
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param SaleslistFactory $saleslistFactory
     * @param AddressFactory $orderAddressFactory
     * @param CountryFactory $countryModel
     * @param MpEmailHelper $mpEmailHelper
     * @param OrdersHelper $ordersHelper
     * @param ProductFactory $productFactory
     * @param OrdersFactory $ordersFactory
     * @param OrderPendingMailsFactory $orderPendingMailsFactory
     * @param NotificationHelper $notificationHelper
     * @param SaleperpartnerFactory $saleperpartnerFactory
     * @param SellerFactory $sellerFactory
     */
    public function __construct(
        \Magento\Framework\Event\Manager $eventManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        SessionManager $coreSession,
        QuoteRepository $quoteRepository,
        OrderRepositoryInterface $orderRepository,
        CustomerRepositoryInterface $customerRepository,
        ProductRepositoryInterface $productRepository,
        MarketplaceHelper $marketplaceHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        SaleslistFactory $saleslistFactory,
        AddressFactory $orderAddressFactory,
        CountryFactory $countryModel,
        MpEmailHelper $mpEmailHelper,
        OrdersHelper $ordersHelper,
        ProductFactory $productFactory,
        OrdersFactory $ordersFactory,
        OrderPendingMailsFactory $orderPendingMailsFactory,
        NotificationHelper $notificationHelper,
        SaleperpartnerFactory $saleperpartnerFactory,
        SellerFactory $sellerFactory
    ) {
        $this->_eventManager = $eventManager;
        $this->_objectManager = $objectManager;
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->_coreSession = $coreSession;
        $this->_quoteRepository = $quoteRepository;
        $this->_orderRepository = $orderRepository;
        $this->_customerRepository = $customerRepository;
        $this->_productRepository = $productRepository;
        $this->_marketplaceHelper = $marketplaceHelper;
        $this->_date = $date;
        $this->saleslistFactory = $saleslistFactory;
        $this->orderAddressFactory = $orderAddressFactory;
        $this->countryModel = $countryModel;
        $this->mpEmailHelper = $mpEmailHelper;
        $this->ordersHelper = $ordersHelper;
        $this->productFactory = $productFactory;
        $this->ordersFactory = $ordersFactory;
        $this->orderPendingMailsFactory = $orderPendingMailsFactory;
        $this->notificationHelper = $notificationHelper;
        $this->saleperpartnerFactory = $saleperpartnerFactory;
        $this->sellerFactory = $sellerFactory;
        parent::__construct(
            $eventManager,
            $objectManager,
            $customerSession,
            $checkoutSession,
            $coreSession,
            $quoteRepository,
            $orderRepository,
            $customerRepository,
            $productRepository,
            $marketplaceHelper,
            $date,
            $saleslistFactory,
            $orderAddressFactory,
            $countryModel,
            $mpEmailHelper,
            $ordersHelper,
            $productFactory,
            $ordersFactory,
            $orderPendingMailsFactory,
            $notificationHelper,
            $saleperpartnerFactory
        );
    }

    /**
     * Get Seller's Product Data.
     *
     * @param \Magento\Sales\Model\Order $order
     * @param int                        $ratesPerCurrency
     *
     * @return array
     */
    public function getSellerProductData($order, $ratesPerCurrency)
    {
        $lastOrderId = $order->getId();
        /*
        * Get Global Commission Rate for Admin
        */
        $percent = $this->_marketplaceHelper->getConfigCommissionRate();

        $sellerProArr = [];
        $sellerTaxArr = [];
        $sellerCouponArr = [];
        $isShippingFlag = [];

        foreach ($order->getAllItems() as $item) {
            $itemData = $item->getData();
            $sellerId = $this->getSellerIdPerProduct($item);
            $calculationStatus = true;
            if ($itemData['product_type'] == 'bundle') {
                $productOptions = $item->getProductOptions();
                $calculationStatus = $productOptions['product_calculations'] ? true : false;
            }
            if ($calculationStatus) {
                $isShippingFlag = $this->getShippingFlag($item, $sellerId, $isShippingFlag);

                $price = $itemData['base_price'];

                $taxamount = $itemData['base_tax_amount'];
                $qty = $item->getQtyOrdered();

                $totalamount = $item->getRowTotal();

                $advanceCommissionRule = $this->_customerSession->getData(
                    'advancecommissionrule'
                );
                $commission = $this->getCommission($sellerId, $totalamount, $item, $advanceCommissionRule);

                $actparterprocost = $totalamount - $commission;
            } else {
                if (empty($isShippingFlag[$sellerId])) {
                    $isShippingFlag[$sellerId] = 0;
                }
                $price = 0;
                $taxamount = 0;
                $qty = $item->getQtyOrdered();
                $totalamount = 0;
                $commission = 0;
                $actparterprocost = 0;
            }

            $collectionsave = $this->saleslistFactory->create();
            $collectionsave->setMageproductId($item->getProductId());
            $collectionsave->setOrderItemId($item->getItemId());
            $collectionsave->setParentItemId($item->getParentItemId());
            $collectionsave->setOrderId($lastOrderId);
            $collectionsave->setMagerealorderId($order->getIncrementId());
            $collectionsave->setMagequantity($qty);
            $collectionsave->setSellerId($sellerId);
            $collectionsave->setCpprostatus(\Webkul\Marketplace\Model\Saleslist::PAID_STATUS_PENDING);
            $collectionsave->setMagebuyerId($this->_customerSession->getCustomerId());
            $collectionsave->setMageproPrice($price);
            $collectionsave->setMageproName($item->getName());
            if ($totalamount != 0) {
                $collectionsave->setTotalAmount($totalamount);
                $commissionRate = ($commission * 100) / $totalamount;
            } else {
                $collectionsave->setTotalAmount($price);
                $commissionRate = $percent;
            }
            $collectionsave->setTotalTax($taxamount);
            if (!$this->_marketplaceHelper->isSellerCouponModuleInstalled()) {
                if (isset($itemData['base_discount_amount'])) {
                    $baseDiscountAmount = $itemData['base_discount_amount'];
                    $collectionsave->setIsCoupon(1);
                    $collectionsave->setAppliedCouponAmount($baseDiscountAmount);

                    if (!isset($sellerCouponArr[$sellerId])) {
                        $sellerCouponArr[$sellerId] = 0;
                    }
                    $sellerCouponArr[$sellerId] = $sellerCouponArr[$sellerId] + $baseDiscountAmount;
                }
            }
            $collectionsave->setTotalCommission($commission);
            $collectionsave->setActualSellerAmount($actparterprocost);
            $collectionsave->setCommissionRate($commissionRate);
            $collectionsave->setCurrencyRate($ratesPerCurrency);
            if (isset($isShippingFlag[$sellerId])) {
                $collectionsave->setIsShipping($isShippingFlag[$sellerId]);
            }
            $collectionsave->setCreatedAt($this->_date->gmtDate());
            $collectionsave->setUpdatedAt($this->_date->gmtDate());
            $collectionsave->save();
            if (!isset($sellerTaxArr[$sellerId])) {
                $sellerTaxArr[$sellerId] = 0;
            }
            $sellerTaxArr[$sellerId] = $sellerTaxArr[$sellerId] + $taxamount;
            if ($price != 0.0000) {
                if (!isset($sellerProArr[$sellerId])) {
                    $sellerProArr[$sellerId] = [];
                }
                array_push($sellerProArr[$sellerId], $item->getProductId());
            } else {
                if (!$item->getParentItemId()) {
                    if (!isset($sellerProArr[$sellerId])) {
                        $sellerProArr[$sellerId] = [];
                    }
                    array_push($sellerProArr[$sellerId], $item->getProductId());
                }
            }
        }

        return [
            'seller_pro_arr' => $sellerProArr,
            'seller_tax_arr' => $sellerTaxArr,
            'seller_coupon_arr' => $sellerCouponArr
        ];
    }

    /**
     * Order Place Operation method.
     *
     * @param \Magento\Sales\Model\Order $order
     * @param int                        $lastOrderId
     */
    public function orderPlacedOperations($order, $lastOrderId)
    {
        $this->productSalesCalculation($order);
        $storeId = $this->_marketplaceHelper->getCurrentStoreId();
        $sellerCollection = $this->sellerFactory->create()
            ->getCollection()->addFieldToFilter('store_id', $storeId);
        $shopTitle = $sellerCollection->getFirstItem()->getShopTitle();

        /*send placed order mail notification to seller*/

        $paymentCode = '';
        if ($order->getPayment()) {
            $paymentCode = $order->getPayment()->getMethod();
        }

        $shippingInfo = '';
        $shippingDes = '';

        $billingId = $order->getBillingAddress()->getId();

        $billaddress = $this->orderAddressFactory->create()->load($billingId);
        $firstNameKana = $billaddress->getExtensionAttributes()->getFirstnamekana();
        $lastNameKana = $billaddress->getExtensionAttributes()->getLastnamekana();
        $nameKana = $lastNameKana.' '.$firstNameKana;
        $billinginfo = $billaddress['lastname'] . ' ' .$billaddress['firstname'].' ('.$nameKana.')<br/>'.
            '〒'.$billaddress['postcode'].'<br/>'.
            $billaddress['region'].
            $billaddress['city'].
            $billaddress['street'].'<br/><br/>
            電話番号:'. $billaddress['telephone'];

        $order->setOrderApprovalStatus(1)->save();

        $payment = $order->getPayment()->getMethodInstance()->getTitle();

        if ($order->getShippingAddress()) {
            $shippingId = $order->getShippingAddress()->getId();
            $address = $this->orderAddressFactory->create()->load($shippingId);
            $shippingInfo = $address['lastname'] . ' ' .$address['firstname'].' ('.$nameKana.')<br/>'.
                '〒'.$billaddress['postcode'].'<br/>'.
                $address['region'].
                $address['city'].
                $address['street'].'<br/><br/>
                電話番号:'. $address['telephone'];
            $shippingDes = $order->getShippingDescription();
        }

        $adminStoremail = $this->_marketplaceHelper->getAdminEmailId();
        $defaultTransEmailId = $this->_marketplaceHelper->getDefaultTransEmailId();
        $adminEmail = $adminStoremail ? $adminStoremail : $defaultTransEmailId;
        $adminUsername = $this->_marketplaceHelper->getAdminName();

        $sellerOrder = $this->ordersFactory->create()
            ->getCollection()
            ->addFieldToFilter('order_id', $lastOrderId)
            ->addFieldToFilter('seller_id', ['neq' => 0]);
        foreach ($sellerOrder as $info) {
            $userdata = $this->_customerRepository->getById($info['seller_id']);
            $username = $userdata->getFirstname();
            $lastName = $userdata->getLastname();
            $useremail = $userdata->getEmail();

            $senderInfo = [];
            $receiverInfo = [];

            $receiverInfo = [
                'name' => $username,
                'email' => $useremail,
            ];
            $senderInfo = [
                'name' => $adminUsername,
                'email' => $adminEmail,
            ];
            $totalprice = 0;
            $totalTaxAmount = 0;
            $codCharges = 0;
            $shippingCharges = 0;
            $orderinfo = '';

            $saleslistIds = [];
            $collection1 = $this->saleslistFactory->create()
                ->getCollection()
                ->addFieldToFilter('order_id', $lastOrderId)
                ->addFieldToFilter('seller_id', $info['seller_id'])
                ->addFieldToFilter('parent_item_id', ['null' => 'true'])
                ->addFieldToFilter('magerealorder_id', ['neq' => 0])
                ->addFieldToSelect('entity_id');

            $saleslistIds = $collection1->getData();

            $fetchsale = $this->saleslistFactory->create()
                ->getCollection()
                ->addFieldToFilter(
                    'entity_id',
                    ['in' => $saleslistIds]
                );
            $fetchsale->getSellerOrderCollection();
            foreach ($fetchsale as $res) {
                $product = $this->_productRepository->getById($res['mageproduct_id']);

                /* product name */
                $productName = $res->getMageproName();
                $result = [];
                $result = $this->getProductOptionData($res, $result);
                /* end */
                if ($res->getProductType() == 'configurable') {
                    $configurableSalesItem = $this->saleslistFactory->create()
                        ->getCollection()
                        ->addFieldToFilter('order_id', $lastOrderId)
                        ->addFieldToFilter('seller_id', $info['seller_id'])
                        ->addFieldToFilter('parent_item_id', $res->getOrderItemId());
                    $configurableItemArr = $configurableSalesItem->getOrderedProductId();
                    $configurableItemId = $res['mageproduct_id'];
                    if (!empty($configurableItemArr)) {
                        $configurableItemId = $configurableItemArr[0];
                    }
                    $product = $this->_productRepository->getById($configurableItemId);
                } else {
                    $product = $this->_productRepository->getById($res['mageproduct_id']);
                }

                $sku = $product->getSku();
                $productName = $this->getProductNameSellerHtml($result, $productName, $sku);
                $orderinfo = $orderinfo."<tbody><tr>
                                <td class='item-info' colspan='2'>".$productName."</td>
                                <td class='item-qty'>".($res['magequantity'] * 1)."</td>
                                <td class='item-price'>".
                    $order->formatPrice(
                        $res['magepro_price'] * $res['magequantity']
                    ).
                    '</td>
                             </tr></tbody>';
                $totalTaxAmount = $totalTaxAmount + $res['total_tax'];
                $totalprice = $totalprice + ($res['magepro_price'] * $res['magequantity']);

                /*
                * Low Stock Notification mail to seller
                */
                if ($this->_marketplaceHelper->getlowStockNotification()) {
                    if (!empty($product['quantity_and_stock_status']['qty'])) {
                        $stockItemQty = $product['quantity_and_stock_status']['qty'];
                    } else {
                        $stockItemQty = $product->getQty();
                    }
                    if ($stockItemQty <= $this->_marketplaceHelper->getlowStockQty()) {
                        $orderProductInfo = "<tbody><tr>
                                <td class='item-info' colspan='2'>".$productName."</td>
                                <td class='item-qty'>".($stockItemQty * 1).'</td>
                             </tr></tbody>';

                        $emailTemplateVariables = [];
                        $emailTemplateVariables['myvar1'] = $orderProductInfo;
                        $emailTemplateVariables['myvar2'] = $username;

                        $this->mpEmailHelper->sendLowStockNotificationMail(
                            $emailTemplateVariables,
                            $senderInfo,
                            $receiverInfo
                        );
                    }
                }
            }
            $shippingCharges = $info->getShippingCharges();
            $couponAmount = $info->getCouponAmount();
            $totalCod = 0;

            if ($paymentCode == 'mpcashondelivery') {
                $totalCod = $info->getCodCharges();
                $codRow = "<tr class='subtotal'>
                            <th colspan='3'>".__('Cash On Delivery Charges')."</th>
                            <td colspan='3'><span>".
                    $order->formatPrice($totalCod).
                    '</span></td>
                            </tr>';
            } else {
                $codRow = '';
            }

            $orderinfo = $orderinfo."<tfoot class='order-totals'>
                                <tr class='subtotal'>
                                    <th colspan='3'>".__('Subtotal')."</th>
                                    <td colspan='3'><span>".
                $order->formatPrice(
                    $res['magepro_price'] * $res['magequantity']
                )."</span></td>
                                </tr>
                                <tr class='subtotal'>
                                    <th colspan='3'>".__('Shipping and handling')."</th>
                                    <td colspan='3'><span>".
                $order->formatPrice($shippingCharges).
                "</span></td>
                                </tr>
                                <tr class='subtotal'>
                                    <th colspan='3'>".__('Internal consumption tax [10%]')."</th>
                                    <td colspan='3'><span>".
                $order->formatPrice($totalTaxAmount).'</span></td>
                                </tr>'.$codRow."
                                <tr class='subtotal'>
                                    <th colspan='3'>".__('Payment  ')."</th>
                                    <td colspan='3'><span>".
                $order->formatPrice(
                    $totalprice +
                    $totalTaxAmount +
                    $shippingCharges +
                    $totalCod -
                    $couponAmount
                ).'</span></td>
                                </tr></tfoot>';

            $emailTemplateVariables = [];
            if ($shippingInfo != '') {
                $isNotVirtual = 1;
            } else {
                $isNotVirtual = 0;
            }
            $emailTempVariables['myvar1'] = $order->getRealOrderId();
            $emailTempVariables['myvar2'] = $order['created_at'];
            $emailTempVariables['myvar4'] = $billinginfo;
            $emailTempVariables['myvar5'] = $payment;
            $emailTempVariables['myvar6'] = $shippingInfo;
            $emailTempVariables['isNotVirtual'] = $isNotVirtual;
            $emailTempVariables['myvar9'] = $shippingDes;
            $emailTempVariables['myvar8'] = $orderinfo;
            $emailTempVariables['myvar3'] = $username;
            $emailTempVariables['last_name'] = $lastName;
            $emailTempVariables['first_name_kana'] = $firstNameKana;
            $emailTempVariables['last_name_kana'] = $lastNameKana;
            $emailTempVariables['shop_title'] = $shopTitle;

            if ($this->_marketplaceHelper->getOrderApprovalRequired()) {
                $emailTempVariables['seller_id'] = $info['seller_id'];
                $emailTempVariables['order_id'] = $lastOrderId;
                $emailTempVariables['sender_name'] = $senderInfo['name'];
                $emailTempVariables['sender_email'] = $senderInfo['email'];
                $emailTempVariables['receiver_name'] = $receiverInfo['name'];
                $emailTempVariables['receiver_email'] = $receiverInfo['email'];

                $orderPendingMailsCollection = $this->orderPendingMailsFactory->create();
                $orderPendingMailsCollection->setData($emailTempVariables);
                $orderPendingMailsCollection->setCreatedAt($this->_date->gmtDate());
                $orderPendingMailsCollection->setUpdatedAt($this->_date->gmtDate());
                $orderPendingMailsCollection->save();
                $order->setOrderApprovalStatus(0)->save();
            } else {
                $this->mpEmailHelper->sendPlacedOrderEmail(
                    $emailTempVariables,
                    $senderInfo,
                    $receiverInfo
                );
            }
        }
    }

    /**
     * Get Order Product Name Html Data Method.
     *
     * @param array $result
     * @param string $productName
     * @param string $sku
     * @return string
     */
    public function getProductNameSellerHtml($result, $productName, $sku)
    {
        if ($_options = $result) {
            $proOptionData = '<dl class="item-options">';
            foreach ($_options as $_option) {
                $proOptionData .= '<dt>'.$_option['label'].'</dt>';

                $proOptionData .= '<dd>'.$_option['value'];
                $proOptionData .= '</dd>';
            }
            $proOptionData .= '</dl>';
            $productName = $productName.'<br/>'.__('SKU: ').$sku.'<br/>'.$proOptionData;
        } else {
            $productName = $productName.'<br/>'.__('SKU: ').$sku;
        }

        return $productName;
    }
}
