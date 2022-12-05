<?php
namespace Mpx\Contact\Model;

use Magento\Contact\Model\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\User\Model\User;
use Psr\Log\LoggerInterface;

/**
 * Contact module configuration
 */
class Config extends \Magento\Contact\Model\Config
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param User $user
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        User $user,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->user = $user;
        $this->logger = $logger;
        parent::__construct($scopeConfig);
    }

    /**
     * Get Email Recipient
     *
     * @return string
     */
    public function emailRecipient()
    {
        try {
            $emailRecipient = $this->scopeConfig->getValue(
                ConfigInterface::XML_PATH_EMAIL_RECIPIENT,
                ScopeInterface::SCOPE_STORE
            );
            $xsAdminEmail = $this->user->loadByUsername('xs-admin')->getEmail();
            return $emailRecipient == 'default_xs-admin_email' ? $xsAdminEmail : $emailRecipient;
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
            return "";
        }
    }
}
