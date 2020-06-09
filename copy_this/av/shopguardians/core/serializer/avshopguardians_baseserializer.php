<?php

/**
 * Class BaseSerializer
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
abstract class avshopguardians_baseserializer
{
    /**
     * Returns absolute url to object
     *
     * @param $object
     * @return string|null
     */
    public static function getDetailUrl($object)
    {
        if (empty($object['seoLink'])) return null;
        $sFullUrl =  oxRegistry::getConfig()->getShopUrl() . $object['seoLink'];

        return oxRegistry::get('oxUtilsUrl')->processSeoUrl($sFullUrl);
    }
}