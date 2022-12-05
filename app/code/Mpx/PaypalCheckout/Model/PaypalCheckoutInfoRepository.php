<?php

namespace Mpx\PaypalCheckout\Model;

use Mpx\PaypalCheckout\Api\Data\PaypalCheckoutInfoInterface;
use Mpx\PaypalCheckout\Api\PaypalCheckoutInfoRepositoryInterface;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfoFactory;
use Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo as ObjectResourceModel;
use Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;


/**
 * class PaypalCheckoutInfoRepository
 * Crud Api PayPal
 */
class PaypalCheckoutInfoRepository implements PaypalCheckoutInfoRepositoryInterface
{
    protected $objectFactory;

    protected $objectResourceModel;

    protected $collectionFactory;

    protected $searchResultsFactory;

    public function __construct(
        PaypalCheckoutInfoFactory $objectFactory,
        ObjectResourceModel $objectResourceModel,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->objectFactory        = $objectFactory;
        $this->objectResourceModel  = $objectResourceModel;
        $this->collectionFactory    = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param PaypalCheckoutInfoInterface $object
     * @return PaypalCheckoutInfoInterface
     * @throws CouldNotSaveException
     */
    public function save(PaypalCheckoutInfoInterface $object): PaypalCheckoutInfoInterface
    {
        try {
            $this->objectResourceModel->save($object);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $object;
    }

    /**
     * @param $id
     * @return PaypalCheckoutInfoInterface
     * @throws NoSuchEntityException
     */
    public function getById($id): PaypalCheckoutInfoInterface
    {
        $object = $this->objectFactory->create();
        $this->objectResourceModel->load($object, $id);
        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Paypal Authorization Info with id "%1" does not exist.', $id));
        }
        return $object;
    }

    /**
     * Retrieve Paypal Checkout Info list.
     *
     * @param SearchCriteriaInterface $criteria
     * @return PaypalCheckoutInfoInterface|\Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $collection = $this->collectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields     = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition    = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[]     = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $objects = [];
        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }
        $searchResults->setItems($objects);
        return $searchResults;
    }

    /**
     * Delete Paypal Checkout Info.
     *
     * @param PaypalCheckoutInfoInterface $object
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(PaypalCheckoutInfoInterface $object): bool
    {
        try {
            $this->objectResourceModel->delete($object);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Paypal Checkout Info by ID.
     *
     * @param $id
     * @return bool true on success
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id): bool
    {
        return $this->delete($this->getById($id));
    }
}
