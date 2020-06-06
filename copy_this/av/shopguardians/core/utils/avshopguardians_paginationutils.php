<?php

/**
 * Class avshopguardianspaginationutils
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_paginationutils
{

    public static function calcTotalPages($totalCount, $perPage)
    {
        return (int) ceil($totalCount / $perPage);
    }

}