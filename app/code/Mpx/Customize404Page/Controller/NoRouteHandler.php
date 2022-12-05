<?php

namespace Mpx\Customize404Page\Controller;

use Magento\Framework\App\RequestInterface as Request;
use Magento\Framework\App\Router\NoRouteHandlerInterface;

class NoRouteHandler implements NoRouteHandlerInterface
{
    /**
     * Set request page 404
     *
     * @param Request $request
     * @return false
     */
    public function process(Request $request): bool
    {
        $request->setModuleName('notfoundpages')
            ->setControllerName('norouter')
            ->setActionName('noroute');
        return false;
    }
}
