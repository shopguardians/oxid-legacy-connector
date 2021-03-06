<?php

/**
 * Class avshopguardians_orderrepository
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_orderrepository
{
    /**
     * Returns the average of orders per hour
     *
     * @return int
     */
    public static function getAverageOrdersPerHour()
    {
        $query = "SELECT AVG(`count`)
FROM (
SELECT COUNT(OXID) AS `count`
FROM oxorder 
      GROUP BY HOUR(OXORDERDATE)
    ) nested;";

        $avgCount = oxDb::getDb(oxDb::FETCH_MODE_NUM)->getOne($query);
        if (!$avgCount) {
            return 0;
        }

        return intval($avgCount);
    }

    /**
     * Returns the distribution of orders for each hour including standard deviation abs and percent
     *
     * @param $avgOrdersPerHour
     * @return array
     */
    public static function getStandardDeviationPerHour($avgOrdersPerHour)
    {
        $avgOrdersPerHour = intval($avgOrdersPerHour);

        $query = "SELECT COUNT(OXID) AS count, ($avgOrdersPerHour-COUNT(OXID)) AS avgDiff, ( (($avgOrdersPerHour-COUNT(OXID))/$avgOrdersPerHour) *100 ) AS avgDiffPercent, HOUR(OXORDERDATE) FROM oxorder 
GROUP BY HOUR(OXORDERDATE)";

        $deviationTable = oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->getAll($query);

        return $deviationTable;
    }

    /**
     * Get average distance in minutes between orders
     * by weekday
     * Can optionally specify an OXPAYMENTTYPE
     *
     * @param $fromDate
     * @param $toDate
     * @param null $paymentMethod (one of o.OXPAYMENTTYPE / oxpayments.OXID)
     * @return false|string
     */
    public static function getAvgMinutesBetweenOrdersInDateRangeByWeekday($fromDate, $toDate, $paymentMethod=null)
    {
        $query = "SELECT TIMESTAMPDIFF(MINUTE, MIN(oxorderdate), MAX(oxorderdate) ) 
       /
       (COUNT(DISTINCT(oxorderdate)) -1) AS avgMinutes, WEEKDAY(OXORDERDATE) AS weekdayNumber
FROM oxorder
WHERE OXORDERDATE >= ? AND OXORDERDATE <= ?

";

        $params = [$fromDate, $toDate];

        if ($paymentMethod !== null) {
            $query .= ' AND OXPAYMENTTYPE = ?';
            $params[] = $paymentMethod;
        }

        $query .= ' GROUP BY WEEKDAY(OXORDERDATE)';

        return oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->getAll($query, $params);
    }

    /**
     * Returns datetime of the newest order
     *
     * @return
     */
    public static function getNewestOrderDateTime()
    {
        $query = "SELECT MAX(OXORDERDATE) FROM oxorder";

        return oxDb::getDb(oxDb::FETCH_MODE_NUM)->getOne($query);
    }

    /**
     * Returns datetime of the newest order for given payment method
     *
     * @param $paymentMethod
     * @return false|string|null
     */
    public static function getNewestOrderDateTimeForPaymentMethod($paymentMethod)
    {
        $query = "SELECT MAX(OXORDERDATE) FROM oxorder WHERE oxpaymenttype = ?";

        return oxDb::getDb(oxDb::FETCH_MODE_NUM)->getOne($query, [$paymentMethod]);
    }

    /**
     * Returns an array of paymentmethod + last used date
     *
     * @return array|null
     */
    public static function getNewestOrderDatesByPaymentMethods()
    {
        $query = "SELECT oxpaymenttype AS paymentMethod, MAX(OXORDERDATE) AS orderDate FROM oxorder 
GROUP BY oxpaymenttype";

        return oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->getAll($query);
    }

    /**
     * Returns an array of payment method usage counts in last XXX days
     *
     * @param $lastDays Number of days
     * @return array|null
     */
    public static function getActivelyUsedPaymentMethodsDaysAgo($lastDays)
    {
        $query = "SELECT o.oxpaymenttype AS paymenttype,p.OXDESC AS description from oxorder o

left join oxpayments p ON p.OXID=o.oxpaymenttype
where p.OXACTIVE = 1 AND o.OXORDERDATE >= NOW() - INTERVAL ? DAY
group by o.oxpaymenttype";

        return oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->getAll($query, [$lastDays]);
    }


}