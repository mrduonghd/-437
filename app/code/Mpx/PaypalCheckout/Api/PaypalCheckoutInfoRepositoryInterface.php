<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_mee231 extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package Magenest_mee231
 */

namespace Mpx\PaypalCheckout\Api;

use \Mpx\PaypalCheckout\Api\Data\PaypalCheckoutInfoInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface PaypalCheckoutInfoRepositoryInterface
{
    /**
     * Save Paypal Checkout Info.
     *
     * @param PaypalCheckoutInfoInterface $object
     * @return PaypalCheckoutInfoInterface
     */
    public function save(PaypalCheckoutInfoInterface $object);

    /**
     * Retrieve Paypal Checkout Info.
     *
     * @param $id
     * @return PaypalCheckoutInfoInterface
     */
    public function getById($id);

    /**
     * Retrieve Checkout Info list.
     *
     * @param SearchCriteriaInterface $criteria
     * @return PaypalCheckoutInfoInterface
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * Delete Paypal Checkout Info.
     *
     * @param PaypalCheckoutInfoInterface $object
     * @return bool true on success
     */
    public function delete(PaypalCheckoutInfoInterface $object);

    /**
     * Delete Paypal Checkout Info by ID.
     *
     * @param $id
     * @return bool true on success
     */
    public function deleteById($id);
}
