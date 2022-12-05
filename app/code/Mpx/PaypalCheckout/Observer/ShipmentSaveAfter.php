<?php

namespace Mpx\PaypalCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\Order;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfoFactory;
use Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Save all shipment at
 *
 * class ShipmentSaveAfter
 */
class ShipmentSaveAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var DateTime
     */
    protected $timezoneInterface;

    /**
     * @var PaypalCheckoutInfoFactory
     */
    private $_paypalCheckoutInfoFactory;

    /**
     * @var PaypalCheckoutInfo
     */
    private $_paypalCheckoutInfoResource;

    /**
     * @var ManagerInterface
     */
    protected $_message;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param PaypalCheckoutInfoFactory $paypalCheckoutInfoFactory
     * @param PaypalCheckoutInfo $paypalCheckoutInfoResource
     * @param DateTime $timezoneInterface
     * @param LoggerInterface $logger
     * @param ManagerInterface $_message
     */
    public function __construct(
        PaypalCheckoutInfoFactory  $paypalCheckoutInfoFactory,
        PaypalCheckoutInfo         $paypalCheckoutInfoResource,
        DateTime                   $timezoneInterface,
        LoggerInterface            $logger,
        ManagerInterface           $_message
    ) {
        $this->_paypalCheckoutInfoFactory = $paypalCheckoutInfoFactory;
        $this->_paypalCheckoutInfoResource = $paypalCheckoutInfoResource;
        $this->timezoneInterface = $timezoneInterface;
        $this->logger = $logger;
        $this->_message = $_message;
    }

    /**
     * Save all shipment at
     *
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $shipment = $observer->getShipment();
        $order = $shipment->getOrder();
        $paypalFactory = $this->_paypalCheckoutInfoFactory->create();
        $paypalFactory->getByIncrementId($order->getIncrementId());
        if ($this->isAllItemShipped($order)) {
            $dateTime = $this->timezoneInterface->gmtDate('Y-m-d H:i:s');
            $paypalFactory->setAllShippingAt($dateTime);
        } else {
            $paypalFactory->setAllShippingAt(null);
        }
        $this->_paypalCheckoutInfoResource->save($paypalFactory);
    }

    /**
     * Check qty shipment
     *
     * @param Order  $order
     * @return bool
     */
    public function isAllItemShipped(Order $order): bool
    {
        $orderItems = $order->getAllVisibleItems();
        foreach ($orderItems as $item) {
            if (round($item->getQtyOrdered()) !== round($item->getQtyShipped())) {
                return false;
            }
        }
        return true;
    }
}
