<?php

/**
 * Class CategoryRepository
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_categoryrepository
{
    /**
     * @param avshopguardians_paginationdata $pagination
     * @return array
     */
    public static function getPoorlyMaintainedCategories(avshopguardians_paginationdata $pagination)
    {
        $query = "SELECT OXID,OXPARENTID,OXACTIVE,OXTITLE,OXDESC,OXTHUMB,CHAR_LENGTH(OXLONGDESC),
 (
    SELECT s.OXSEOURL FROM oxseo s
    WHERE s.oxobjectid = OXID
    AND s.OXLANG = 0
    ORDER BY OXTIMESTAMP DESC
    LIMIT 1
 ) as seoLink FROM oxcategories 
WHERE OXDESC = '' OR OXLONGDESC = '' OR OXTHUMB = ''
ORDER BY OXACTIVE DESC, OXTITLE
LIMIT {$pagination->getOffset()},{$pagination->getPerPage()}";

        return oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->getAll($query);

    }

    /**
     * @return int
     */
    public static function getPoorlyMaintainedCategoriesCount()
    {
        $query = "SELECT COUNT(OXID) FROM oxcategories 
WHERE OXDESC = '' OR OXLONGDESC = '' OR OXTHUMB = ''";

        $totalCount = oxDb::getDb(oxDb::FETCH_MODE_NUM)->getOne($query);
        if (!$totalCount) {
            return 0;
        }

        return intval($totalCount);

    }

}