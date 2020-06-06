<?php
/**
 * Class avshopguardiansoxarticle
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_oxarticle extends avshopguardians_oxarticle_parent
{
    /**
     * Returns short description field
     *
     * @return null|string
     */
    public function getShortDescription()
    {
        return isset($this->oxarticles__oxshortdesc->value) ? $this->oxarticles__oxshortdesc->value : null;
    }

    /**
     * Returns name of assigned main category
     *
     * @return null|string
     */
    public function getCategoryName()
    {
        $oCategory = $this->getCategory();

        if ($oCategory instanceof oxCategory) {
            return $oCategory->getTitle();
        }

        return null;
    }

    /**
     * Returns name of assigned manufacturer
     *
     * @return null|string
     */
    public function getManufacturerName()
    {
        $oManufacturer = $this->getManufacturer();

        if ($oManufacturer instanceof oxManufacturer) {
            return $oManufacturer->getTitle();
        }

        return null;
    }

    /**
     * Returns a list of variant keys or an empty array if none
     *
     * @return array
     */
    public function getVariantKeys()
    {
        if ($this->getVariantsCount() == 0) {
            return [];
        }

        $variantHandler = oxRegistry::get('oxVariantHandler');
        return $variantHandler->getSelections($this->oxarticles__oxvarname->value);
    }

    /**
     * Returns a list of key value pairs of the selected MD variants
     *
     * @return array|null
     */
    public function getVariantValues()
    {
        if ($this->oxarticles__oxvarselect->value == '') {
            return null;
        }

        $oParentArticle = $this->getParentArticle();
        if (!$oParentArticle instanceof oxArticle) {
            return null;
        }

        $oVariantHandler    = oxRegistry::get('oxVariantHandler');
        $aVariantKeys       = $oParentArticle->getVariantKeys();
        $aVariantValues     = $oVariantHandler->getSelections($this->oxarticles__oxvarselect->value);

        $aVariantSelections = [];

        foreach ($aVariantValues as $key=>$value) {
            $aVariantSelections[] = [
                'key' => $aVariantKeys[$key],
                'value' => $value
            ];
        }

        return $aVariantSelections;

    }
}