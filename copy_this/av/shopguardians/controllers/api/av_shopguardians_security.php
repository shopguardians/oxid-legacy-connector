<?php

require_once __DIR__ . '/av_shopguardians_basecontroller.php';

/**
 * Class SecurityController
 * @package ActiveValue\Shopguardians\Controller\Api
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class av_shopguardians_security extends av_shopguardians_basecontroller
{

    /**
     * Returns server and shop software version numbers
     *
     * @return array
     * @throws \Exception
     */
    public function getVersions()
    {

        $versions = [
            'shop'                  => [
                'version' =>            oxRegistry::getConfig()->getVersion(),
                'edition' =>            oxRegistry::getConfig()->getEdition()
            ],

            'server' => [
                'php'                   => PHP_VERSION_ID,
                'software'              => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : null,
                'signature'             => isset($_SERVER['SERVER_SIGNATURE']) ? $_SERVER['SERVER_SIGNATURE'] : null,

                'database' => [
                    'type' => oxRegistry::getConfig()->getConfigParam('dbType')

                ]
            ],
        ];

        if (function_exists('apache_get_version')) {
            $versions['server']['apache'] = apache_get_version();
        }

        try {
            $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);
            $versions['server']['database']['version']  = $oDb->getOne('SELECT VERSION()');

        } catch (\Exception $e) {

        }

        $this->renderJson($versions);
    }
}