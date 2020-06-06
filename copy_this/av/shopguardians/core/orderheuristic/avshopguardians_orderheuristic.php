<?php


/**
 * Class OrderHeuristic
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_orderheuristic
{
    /**
     * If standard deviation is greater than this percentage,
     * will do not count this as typical working hour
     */
    protected $standardDeviationTresholdPercent = 50;

    /**
     * If there is no order in [avgMinutesBetweenOrders * this factor] minutes, we will raise an alert
     */
    protected $alertMinutesSafetyBufferFactor = 5;

    /**
     * In case there is not a single order (on a fresh shop) we dont' have a distance,
     * we will need to re-seed later and start with that value
     */
    protected $averageOrderDistanceMinutesFallback = 120;

    /**
     * There should be an order in at least (this) days
     * so a payment method will be considered as actively used
     *
     * @var int
     */
    protected $paymentMethodActivitySpanDays = 120;

    /**
     * Cached first day of last month
     *
     * @var string
     */
    protected $startDayLastMonth;

    /**
     * Cached last day of last month
     *
     * @var string
     */
    protected $endDayLastMonth;

    /**
     * Load config values
     *
     * OrderHeuristic constructor.
     */
    public function __construct()
    {
        $standardDeviationTresholdPercent = avshopguardians_events::getSetting('OHS_DEVIATION_TRESHOLD');
        if ($standardDeviationTresholdPercent) {
            $this->standardDeviationTresholdPercent = $standardDeviationTresholdPercent;
        }
        $alertMinutesSafetyBufferFactor   = avshopguardians_events::getSetting('OHS_SAFETY_BUFFER_FACTOR');
        if ($alertMinutesSafetyBufferFactor) {
            $this->alertMinutesSafetyBufferFactor = $alertMinutesSafetyBufferFactor;
        }
        $paymentMethodActivitySpanDays    = avshopguardians_events::getSetting('OHS_PAYMENTMETHOD_ACTIVITY_DAYS');
        if ($paymentMethodActivitySpanDays) {
            $this->paymentMethodActivitySpanDays = $paymentMethodActivitySpanDays;
        }

        $this->startDayLastMonth  = (new \DateTime( 'first day of last month' ))->format('Y-m-d');
        $this->endDayLastMonth    = (new \DateTime( 'last day of last month' ))->format('Y-m-d');
    }

    /**
     * Returns an array of hours in which most orders are happening
     *
     * @return array
     */
    public function getUsualWorkingHours()
    {
        $avgOrdersPerHour   = avshopguardians_orderrepository::getAverageOrdersPerHour();
        $deviationTable     = avshopguardians_orderrepository::getStandardDeviationPerHour($avgOrdersPerHour);

        $workingHours = [];

        foreach ($deviationTable as $key=>$row) {
            if ($row['avgDiffPercent'] <= $this->standardDeviationTresholdPercent) {
                $workingHours[] = $row['HOUR(OXORDERDATE)'];
            }
        }

        return $workingHours;
    }

    /**
     * Returns the average distance between orders in minutes
     * for the last month
     *
     * @return false|string
     */
    public function getAverageMinutesBetweenOrders()
    {
        $avgDistance = avshopguardians_orderrepository::getAverageMinutesBetweenOrdersInDateRange($this->startDayLastMonth, $this->endDayLastMonth);

        return $avgDistance ? $avgDistance : $this->averageOrderDistanceMinutesFallback;
    }

    /**
     * Returns the average order distance for a single payment method in minutes
     * for the last month
     *
     * @param $paymentType
     * @return false|int|string
     */
    public function getAverageMinutesBetweenOrdersForPaymentType($paymentType)
    {
        return avshopguardians_orderrepository::getAverageMinutesBetweenOrdersInDateRange($this->startDayLastMonth, $this->endDayLastMonth, $paymentType);
    }

    /**
     * Returns the assumed timerange in minutes where a order should happen
     * otherwise alert would be raised
     *
     * @return false|float
     */
    public function getAssumedOrderDistance()
    {
        $averageOrderDistanceMinutes = $this->getAverageMinutesBetweenOrders();

        return round($averageOrderDistanceMinutes * $this->alertMinutesSafetyBufferFactor);
    }

    /**
     * Returns the assumed timerange in minutes where a order should happen with the selected
     * payment method, otherwise alert would be rised
     *
     * e.g. credt card orders are assumed every 20 mins
     *
     * Returns array of key=paymentmethod, value=mins
     *
     * @param $paymentMethods
     * @return array
     */
    public function getAssumedOrderDistanceByPaymentMethods($paymentMethods)
    {
        $distances = [];

        if (empty($paymentMethods)) {
            return $distances;
        }

        foreach ($paymentMethods as $paymentMethod) {
            $distances[] = [
                'minutes' => round($this->getAverageMinutesBetweenOrdersForPaymentType($paymentMethod['paymenttype']) * $this->alertMinutesSafetyBufferFactor),
                'paymenttype' => $paymentMethod['paymenttype'],
                'description' => $paymentMethod['description']
            ];
        }

        return $distances;
    }

    /**
     * Returns actively used payment methods
     *
     * @return array|null
     */
    public function getRelevantPaymentMethods()
    {
        return avshopguardians_orderrepository::getActivelyUsedPaymentMethodsDaysAgo($this->paymentMethodActivitySpanDays);
    }

}