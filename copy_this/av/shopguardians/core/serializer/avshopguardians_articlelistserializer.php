<?php

/**
 * Class avshopguardiansarticlelistserializer
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_articlelistserializer
{
    /**
     * Turn this item object into a generic array
     *
     * @param array $articles
     * @return array
     */
    public static function transform(array $articles)
    {
        $serialized = [];

        foreach ($articles as $article) {
            $serialized[] = [
                'product_uid'          => $article['OXID'],
                'stock'                => (int) $article['OXSTOCK'],
                'title'                => self::getTitle($article),
                'parent_id'            => $article['OXPARENTID'],
                'artnum'               => $article['OXARTNUM'],
                'thumb'                => self::getThumbnailUrl($article),
                'url'                  => self::getDetailUrl($article)
            ];
        }

        return $serialized;
    }

    /**
     * Returns full url to article
     *
     * @param $article
     * @return string
     */
    public static function getDetailUrl($article)
    {
        if (empty($article['seoLink'])) return null;
        $sFullUrl =  oxRegistry::getConfig()->getShopUrl() . $article['seoLink'];
        return oxRegistry::get('oxUtilsUrl')->processSeoUrl($sFullUrl);
    }

    /**
     * Return thumbnail url for first article picture
     *
     * @param $article
     * @return bool|string
     */
    public static function getThumbnailUrl($article)
    {
        $sDirname = "product/1/";
        $sImgName = basename($article['OXPIC1']);

        $sSize = oxRegistry::getConfig()->getConfigParam('sThumbnailsize');

        return oxRegistry::get('oxPictureHandler')->getProductPicUrl($sDirname, $sImgName, $sSize, 0);
    }

    /**
     * Returns article title
     *
     * @param $article
     * @return string
     */
    public static function getTitle($article)
    {
        $sVariantName = $article['PARENTTITLE'] . ' ' . $article['OXVARSELECT'];

        return !empty($article['OXTITLE']) ? $article['OXTITLE'] : $sVariantName;
    }
}