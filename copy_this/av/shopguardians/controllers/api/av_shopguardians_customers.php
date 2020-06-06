<?php

require_once __DIR__ . '/av_shopguardians_basecontroller.php';

/**
 * Class CustomerController
 * @package ActiveValue\Shopguardians\Controller\Api
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class av_shopguardians_customers extends av_shopguardians_basecontroller
{
    /**
     * Returns Key Performance Indicators for orders
     * @TODO: Subshop support
     *
     * @return array
     */
    public function getKPI()
    {
        // Returns number of orders, revenue, avg cart value e.g.
        $oUser = oxNew('oxUser');

        $kpi = [
            'customers_total'       => $oUser->getUserCount(),
            'customers_today'       => $oUser->getNewUserCount(),
            'newsletter_today'      => $oUser->getNewSubscriberCount()
        ];

        return $this->renderJson($kpi);


    }
}