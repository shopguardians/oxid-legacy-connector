<?php
/**
 * Class avshopguardians_events
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_events
{
    /**
     * Module activation script.
     */
    public static function onActivate()
    {
        self::setRandomApiKey();
    }

    /**
     * Module deactivation script.
     */
    public static function onDeactivate()
    {

    }

    /**
     * Get module setting value.
     *
     * @param string  $sModuleSettingName Module setting parameter name (key).
     * @param boolean $blUseModulePrefix  If True - adds the module settings prefix, if False - not.
     *
     * @return mixed
     */
    public static function getSetting($sModuleSettingName, $blUseModulePrefix = true)
    {
        if ($blUseModulePrefix) {
            $sModuleSettingName = 'AVSHOPGUARDIANS_' . (string) $sModuleSettingName;
        }

        return oxRegistry::getConfig()->getConfigParam((string) $sModuleSettingName);
    }

    /**
     * Sets random key in module configuration
     *
     */
    public static function setRandomApiKey()
    {
        if (!empty(self::getSetting('API_KEY'))) {
            return;
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            $randomKey = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $randomKey = md5(uniqid(mt_rand(), true));
        }

        oxRegistry::getConfig()->saveShopConfVar('str', 'AVSHOPGUARDIANS_API_KEY', $randomKey, null, 'module:avshopguardians');
    }
}