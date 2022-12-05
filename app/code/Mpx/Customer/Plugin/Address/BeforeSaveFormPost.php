<?php
namespace Mpx\Customer\Plugin\Address;

use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Psr\Log\LoggerInterface;
use Exception;

class BeforeSaveFormPost
{
    private const DESTINATION_POST_CODE_REGEX_ERROR_CODE = "postcode_regex";
    private const DESTINATION_POST_CODE_REGEX_ERROR_MESSAGE = "Postal code is not correct.";

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param ManagerInterface $messageManager
     * @param RedirectFactory $redirectFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ManagerInterface       $messageManager,
        RedirectFactory        $redirectFactory,
        LoggerInterface        $logger
    ) {
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        $this->logger = $logger;
    }

    /**
     * Function to run to validate post code.
     *
     * @param \Magento\Customer\Controller\Address\FormPost $subject
     * @param callable $process
     * @return Redirect
     */
    public function aroundExecute(
        \Magento\Customer\Controller\Address\FormPost $subject,
        callable $process
    ): Redirect {
        $params = $subject->getRequest()->getParams();
        $postCode = $params['postcode'];
        if (isset($postCode)) {
            $postCode = str_replace("-", "", $postCode);
            $subject->getRequest()->setParam('postcode', $postCode);
        }
        $this->validatePostCode($params);

        if (!empty($this->errors)) {
            try {
                $this->processAddErrorMessage($this->errors);
                $this->cleanErrors();
                return $this->redirectFactory->create()->setPath('customer/address/edit');
            } catch (Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }
        return $process();
    }

    /**
     * Add all errors message founds
     *
     * @param array $errors
     * @return void
     */
    protected function processAddErrorMessage(array $errors): void
    {
        if (!empty($errors)) {
            foreach ($errors as $error) {
                if (isset($error['message'])) {
                    $this->messageManager
                        ->addErrorMessage(__($error['message']));
                }
            }
        }
    }

    /**
     * Clean up error(s)
     *
     * @return void
     */
    protected function cleanErrors(): void
    {
        if (!empty($this->errors)) {
            $this->errors[] = [];
        }
    }

    /**
     * Validate Post Code
     *
     * @param array $params
     * @return void
     */
    protected function validatePostCode(array $params): void
    {
        $postCode = $params['postcode'];
        if (isset($postCode)) {
            if (!preg_match('/^[0-9]{3}-?[0-9]{4}$/', $postCode)) {
                $this->errors[] = [
                    'type' => self::DESTINATION_POST_CODE_REGEX_ERROR_CODE,
                    'message' => self::DESTINATION_POST_CODE_REGEX_ERROR_MESSAGE
                ];
            }
        }
    }
}
