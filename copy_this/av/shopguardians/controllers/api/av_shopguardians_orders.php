<?php

require_once __DIR__ . '/av_shopguardians_basecontroller.php';

/**
 * Class OrderController
 * @package ActiveValue\Shopguardians\Controller\Api
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class av_shopguardians_orders extends av_shopguardians_basecontroller
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
        $oOrder = oxNew('oxOrder');

        /** @var Order $oOrder */

        $kpi = [
            'orders_total' => $oOrder->getOrderCnt(),
            'orders_today' => $oOrder->getOrderCnt(true),
            'revenue_total' => $oOrder->getOrderSum(),
            'revenue_today' => $oOrder->getOrderSum(true)
        ];

        return $this->renderJson($kpi);
    }

    /**
     * Returns the seed data to setup order heuristic check

     */
    public function getOrderHeuristicSeedData()
    {
        $orderHeuristic = oxNew('avshopguardians_orderheuristic');

        $paymentMethods = $orderHeuristic->getRelevantPaymentMethods();

        return $this->renderJson([
            'usualWorkingHours' => $orderHeuristic->getUsualWorkingHours(),
            'averageOrderDistancesByWeekday' => $orderHeuristic->getOrderDistancesByWeekday(),
            'averageOrderDistancesByWeekdayAndPaymentMethods' => $orderHeuristic->getOrderDistancesByWeekdayAndPaymentMethods($paymentMethods)
        ]);
    }

    /**
     */
    public function getNewestOrderDate()
    {
        return $this->renderJson(avshopguardians_orderrepository::getNewestOrderDateTime());
    }

    /**
     * Returns newest order date for given payment method
     * ?paymentMethod=[oxid]
     *
     */
    public function getNewestOrderDateForPaymentMethod()
    {
        $paymentMethod = oxRegistry::getConfig()->getRequestParameter('paymentMethod');
        if (empty($paymentMethod)) {
            avshopguardians_responsehelper::internalServerError('Parameter paymentMethod must not be empty');
        }

        return $this->renderJson(avshopguardians_orderrepository::getNewestOrderDateTimeForPaymentMethod($paymentMethod));
    }

    /**
     * Returns an array of payment method + last time used
     *
     */
    public function getNewestOrderDatesByPaymentMethods()
    {
        return $this->renderJson(avshopguardians_orderrepository::getNewestOrderDatesByPaymentMethods());
    }


}