<?php

/**
 * Class avshopguardiansarticlerepository
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_articlerepository
{

    /**
     * @param $blacklistQuery string
     * @param $perPage int
     * @return int
     */
    public static function getCountForArticlesWithoutCategory($blacklistQuery)
    {
        $query = "SELECT count(*)
FROM oxarticles a
LEFT JOIN oxobject2category o2c ON o2c.`OXOBJECTID` = a.OXID
LEFT JOIN oxartextends ae ON ae.OXID = a.OXID
WHERE o2c.OXOBJECTID IS NULL
AND a.OXACTIVE = ? AND a.OXPARENTID = '' $blacklistQuery";
        $totalCount = oxDb::getDb(oxDb::FETCH_MODE_NUM)->getOne($query, [1]);
        if (!$totalCount) {
            return 0;
        }
        return intval($totalCount);
    }

}