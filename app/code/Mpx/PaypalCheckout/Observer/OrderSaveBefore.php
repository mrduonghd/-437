<?php

namespace Mpx\PaypalCheckout\Observer;


use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\Order;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfoFactory;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfoRepository;
use Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 *Class OrderSaveAfter
 */
class OrderSaveBefore implements ObserverInterface
{
    const CODE                       = 'paypal_checkout';
    const INTENT_AUTHORIZE           = 'AUTHORIZE';
    const PAYPAL_AUTHORIZATION_PERIOD       = 'authorization_period';
    const PAYPAL_AUTHORIZATION_HONOR_PERIOD = 'honor_period';
    const PAYPAL_METHOD = ['paypal_checkout','paypalcc'];
    const PAYPAL_STATUS = [
        'Authorized'             => 'authorized',
        'AuthorizationExpired'  => 'authority_expired',
        'Captured'               => 'captured'
    ];
    const FORMAT_DATE = "Y-m-d H:i:s";


    /** @var Session */
    protected $_checkoutSession;

    /**
     * @var PaypalCheckoutInfoFactory
     */
    protected $paypalCheckoutInfoFactory;

    /** @var ScopeConfigInterface */
    protected $_scopeConfig;

    /**
     * @var DateTime
     */
    protected $time;

    /**
     * @var PaypalCheckoutInfoRepository
     */
    protected $paypalCheckoutInfoRepository;

    protected $collectionFactory;

    /**
     * @param PaypalCheckoutInfoFactory $paypalCheckoutInfoFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param PaypalCheckoutInfoRepository $paypalCheckoutInfoRepository
     * @param Session $checkoutSession
     * @param CollectionFactory $collectionFactory
     * @param DateTime $time
     */
    public function __construct(
        PaypalCheckoutInfoFactory    $paypalCheckoutInfoFactory,
        ScopeConfigInterface         $scopeConfig,
        PaypalCheckoutInfoRepository $paypalCheckoutInfoRepository,
        Session                      $checkoutSession,
        CollectionFactory            $collectionFactory,
        DateTime                     $time
    )
    {
        $this->paypalCheckoutInfoFactory = $paypalCheckoutInfoFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->paypalCheckoutInfoRepository = $paypalCheckoutInfoRepository;
        $this->_checkoutSession = $checkoutSession;
        $this->collectionFactory = $collectionFactory;
        $this->time = $time;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer): void
    {
        /** @var $order Order */
        $order = $observer->getOrder();

        $method = $order->getPayment()->getMethod();
        if (!in_array($method,self::PAYPAL_METHOD)){
           return;
        }
        if (!$order->isObjectNew()) {
            return;
        }

        $paypalCheckoutInfo = $this->paypalCheckoutInfoFactory->create();
        $orderIncrementId = $order->getIncrementId();
        $payment = $order->getPayment();
        $settlementAmount = $payment->getAdditionalInformation('settlement_amount');
        $createTime = $payment->getAdditionalInformation('create_time');
        $authorizationPeriod = $this->getAuthorizationPeriod($createTime);
        $honorPeriod = $this->getHonorPeriod($createTime);
        $paypalCheckoutInfo->setOrderIncrementId($orderIncrementId);
        $paypalCheckoutInfo->setSettlementAmount($settlementAmount);
        if ($payment->getAdditionalInformation('intent') === self::INTENT_AUTHORIZE) {
            $authorizationID = $payment->getAdditionalInformation('authorization_id');
            $paypalCheckoutInfo->setPayPalAuthorizationId($authorizationID);
            $paypalCheckoutInfo->setPayPalAuthorizationPeriod($authorizationPeriod);
            $paypalCheckoutInfo->setPayPalHonorPeriod($honorPeriod);
            $paypalCheckoutInfo->setPayPalStatus(self::PAYPAL_STATUS['Authorized']);
            $paypalCheckoutInfo->setPayPalAuthorizeAt($createTime);
        } else {
            $capturedID = $payment->getAdditionalInformation('captured_id');
            $paypalCheckoutInfo->setPayPalCaptureId($capturedID);
            $paypalCheckoutInfo->setPayPalStatus(self::PAYPAL_STATUS['Captured']);
            $paypalCheckoutInfo->setPayPalCapturedAt($createTime);
        }
        $this->paypalCheckoutInfoRepository->save($paypalCheckoutInfo);
    }

    /**
     * @param $field
     * @return mixed
     */
    public function getConfigValue($field)
    {
        return $this->_scopeConfig->getValue(
            $this->_preparePathConfig($field),
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $field
     * @return string
     */
    protected function _preparePathConfig($field): string
    {
        return sprintf('payment/%s/%s', self::CODE, $field);
    }

    /**
     * @param $date
     * @return mixed
     */
    public function formatDate($date){

        return date(self::FORMAT_DATE,$date);
    }

    /**
     * @param $createTime
     * @return mixed
     */
    public function getAuthorizationPeriod($createTime){
    $configPeriod = $this->getConfigValue(self::PAYPAL_AUTHORIZATION_PERIOD);
    $authorizationPeriod = strtotime( $createTime . ' + ' . $configPeriod . 'days' );
    return $this->formatDate($authorizationPeriod);
    }

    /**
     * @param $createTime
     * @return mixed
     */
    public function getHonorPeriod($createTime){
    $configHonorPeriod = $this->getConfigValue(self::PAYPAL_AUTHORIZATION_HONOR_PERIOD);
    $authorizationPeriod = strtotime( $createTime . ' + ' . $configHonorPeriod . 'days' );
    return $this->formatDate($authorizationPeriod);
    }


}
