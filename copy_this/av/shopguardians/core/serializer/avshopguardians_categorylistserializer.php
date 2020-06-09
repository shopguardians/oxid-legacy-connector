<?php

require_once __DIR__ . '/avshopguardians_baseserializer.php';

/**
 * Class avshopguardians_categorylistserializer
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_categorylistserializer extends avshopguardians_baseserializer
{
    /**
     * Turn this item object into a generic array
     *
     * @param array $categories
     * @return array
     */
    public static function transform(array $categories)
    {
        $serialized = [];

        foreach ($categories as $category) {
            $serialized[] = [
                'category_uid'              => $category['OXID'],
                'active'                    => (int) $category['OXACTIVE'],
                'title'                     => $category['OXTITLE'],
                'parent_id'                 => $category['OXPARENTID'],
                'description'               => $category['OXDESC'],
                'full_description_length'   => (int) $category['CHAR_LENGTH(OXLONGDESC)'],
                'thumb'                     => self::getThumbnailUrl($category),
                'url'                       => self::getDetailUrl($category)
            ];
        }

        return $serialized;
    }

    /**
     * Return thumbnail url for category
     *
     * @param $category
     * @return null|false|string
     */
    public static function getThumbnailUrl($category)
    {
        if (($sIcon = $category['OXTHUMB'])) {
            $sSize = oxRegistry::getConfig()->getConfigParam('sCatThumbnailsize');

            return oxRegistry::get('oxPictureHandler')->getPicUrl("category/thumb/", $sIcon, $sSize);
        }

        return null;
    }

}