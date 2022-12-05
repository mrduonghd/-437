<?php
declare(strict_types=1);

namespace Mpx\PaypalCheckout\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Mpx\PaypalCheckout\Api\Data\PaypalCheckoutInfoInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class PaypalCheckoutInfo extends AbstractModel implements PaypalCheckoutInfoInterface, IdentityInterface
{

    /**
    * Cache tag paypal
    */
    const CACHE_TAG = 'paypal_checkout_info';

    /**
     * @var DateTime
     */
    protected $time;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'paypal_checkout_info';

    /**
     * @param Context $context
     * @param Registry $registry
     * @param DateTime $time
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DateTime $time,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->time = $time;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Init resource model and id field
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo::class);
        $this->setIdFieldName('id');
    }

    /**
     * Get cache identities
     *
     * @return array
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }


    /**
     * @param $incrementId
     * @return PaypalCheckoutInfo
     */
    public function getByIncrementId($incrementId): PaypalCheckoutInfo
    {
        return $this->load($incrementId, PaypalCheckoutInfoInterface::ORDER_INCREMENT_ID);
    }

    /**
     * Get order increment ID
     *
     * @return int
     */
    public function getOrderIncrementId(): int
    {
        return $this->getData(PaypalCheckoutInfoInterface::ORDER_INCREMENT_ID);
    }

    /**
     * Get authorization id
     *
     * @return string
     */
    public function getPayPalAuthorizationId(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZATION_ID);
    }

    /**
     * Get Authorization Period
     *
     * @return string
     */
    public function getPayPalAuthorizationPeriod(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZATION_PERIOD);
    }

    /**
     * Get Honor Period
     *
     * @return string
     */
    public function getPayPalHonorPeriod(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_HONOR_PERIOD);
    }

    /**
     * Get paypal status
     *
     * @return string
     */
    public function getPayPalStatus(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_STATUS);
    }

    /**
     * Get create at
     *
     * @return string
     */
    public function getCreateAt(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::CREATE_AT);
    }

    /**
     * Get update at
     *
     * @return string
     */
    public function getUpdateAt(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::UPDATE_AT);
    }

    /**
     * Get authorize at
     *
     * @return string
     */
    public function getPayPalAuthorizeAt(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZED_AT);
    }

    /**
     * Set Order Increment ID
     *
     * @param $orderIncrementId
     * @return PaypalCheckoutInfoInterface
     */
    public function setOrderIncrementId($orderIncrementId)
    {
        return $this->setData(PaypalCheckoutInfoInterface::ORDER_INCREMENT_ID, $orderIncrementId);
    }

    /**
     * Set Settlement Amount
     *
     * @param $settlementAmount
     * @return PaypalCheckoutInfoInterface
     */
    public function setSettlementAmount($settlementAmount) : PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::ORDER_SETTLEMENT_AMOUNT, $settlementAmount);
    }

    /**
     * Set capture id
     *
     * @param $captureId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalCaptureId($captureId): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_CAPTURE_ID, $captureId);
    }

    /**
     * Set authorization id
     *
     * @param $authorizationId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalAuthorizationId($authorizationId): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZATION_ID, $authorizationId);
    }

    /**
     * Set authorization id
     *
     * @param $authorizationPeriod
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalAuthorizationPeriod($authorizationPeriod): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZATION_PERIOD, $authorizationPeriod);
    }

    /**
     * Set honor period
     *
     * @param $honorPeriod
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalHonorPeriod($honorPeriod): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_HONOR_PERIOD, $honorPeriod);
    }

    /**
     * Set paypal status
     *
     * @param $status
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalStatus($status): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_STATUS, $status);
    }

    /**
     * Set create at
     *
     * @param $createAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setCreateAt($createAt): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::CREATE_AT, $createAt);
    }

    /**
     * Set update at
     *
     * @param $updateAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setUpdateAt($updateAt): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::UPDATED_AT, $updateAt);
    }

    /**
     * Set authorize at
     *
     * @param $authorizeAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalAuthorizeAt($authorizeAt): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZED_AT, $authorizeAt);
    }
    /**
     * Set captured at
     *
     * @param $capturedAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalCapturedAt($capturedAt): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_CAPTURED_AT, $capturedAt);
    }

    public function getAllShippingAt()
    {
        return $this->getData(PaypalCheckoutInfoInterface::ALL_SHIPPING_AT);
    }

    public function setAllShippingAt($allShippingAt)
    {
        return $this->setData(PaypalCheckoutInfoInterface::ALL_SHIPPING_AT, $allShippingAt);
    }
}
