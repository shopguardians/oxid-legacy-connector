<?php
/**
 * Class avshopguardiansoxuser
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_oxuser extends avshopguardians_oxuser_parent
{
    /**
     * Returns the amount of users matching specified criteria
     *
     * @return int
     */
    public function getUserCount($whereSql='')
    {
        $oDb = oxDb::getDb();
        $sQ = "select count(*) from oxuser where oxshopid = '" . $this->getConfig()->getShopId() . "' $whereSql";
        $iCnt = (int) $oDb->getOne($sQ);

        return $iCnt;
    }

    /**
     * Returns number of users that registered today
     *
     * @return int
     */
    public function getNewUserCount()
    {
        return $this->getUserCount('AND DATE(OXCREATE) = CURDATE()');
    }

    /**
     * Returns number of todays newsletter subscribers
     *
     * @return int
     */
    public function getNewSubscriberCount()
    {
        $oDb = oxDb::getDb();
        $sQ = "select count(*) from oxnewssubscribed where DATE(OXSUBSCRIBED) = CURDATE() AND OXDBOPTIN = 1";
        $iCnt = (int) $oDb->getOne($sQ);

        return $iCnt;
    }
}