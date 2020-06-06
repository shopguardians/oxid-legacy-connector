<?php
/**
 *
 * @category      module
 * @package       module
 * @author        active value GmbH
 * @link          http://active-value.de
 * @copyright (C) active value GmbH, 2017-2018
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = [
    'id'          => 'avshopguardians',
    'title'       => '<span style="color: #706f6f;">SHOP</span><span style="color: #000;"><strong>GUARDIANS</strong></span> Connector',
    'description' => 'Shopguardians connector',
    'thumbnail'   => 'img.png',
    'version'     => '1.0.2',
    'author'      => 'Shopguardians',
    'url'         => 'https://shopguardians.de',
    'email'       => 'support@shopguardians.de',
    'extend'      => [
        'oxarticle' => 'av/shopguardians/model/avshopguardians_oxarticle',
        'oxvarianthandler' => 'av/shopguardians/model/avshopguardians_oxvarianthandler',
        'oxuser' => 'av/shopguardians/model/avshopguardians_oxuser'

    ],
    'files' => [
        /** Controllers */
        'av_shopguardians_security' => 'av/shopguardians/controllers/api/av_shopguardians_security.php',
        'av_shopguardians_articles' => 'av/shopguardians/controllers/api/av_shopguardians_articles.php',
        'av_shopguardians_orders' => 'av/shopguardians/controllers/api/av_shopguardians_orders.php',
        'av_shopguardians_customers' => 'av/shopguardians/controllers/api/av_shopguardians_customers.php',

        /** Core */
        'avshopguardians_events' => 'av/shopguardians/core/avshopguardians_events.php',
        'avshopguardians_orderheuristic' => 'av/shopguardians/core/orderheuristic/avshopguardians_orderheuristic.php',
        'avshopguardians_articlelistserializer' => 'av/shopguardians/core/serializer/avshopguardians_articlelistserializer.php',
        'avshopguardians_articleserializer' => 'av/shopguardians/core/serializer/avshopguardians_articleserializer.php',
        'avshopguardians_paginationdata' => 'av/shopguardians/core/utils/avshopguardians_paginationdata.php',
        'avshopguardians_paginationutils' => 'av/shopguardians/core/utils/avshopguardians_paginationutils.php',
        'avshopguardians_responsehelper' => 'av/shopguardians/core/avshopguardians_responsehelper.php',
        'avshopguardians_articlerepository' => 'av/shopguardians/repositories/avshopguardians_articlerepository.php',
        'avshopguardians_orderrepository' => 'av/shopguardians/repositories/avshopguardians_orderrepository.php'
    ],
    'templates'   => [
    ],
    'blocks'      => [

    ],
    'settings'    => [

        ['group' => 'avshopguardians_main', 'name' => 'AVSHOPGUARDIANS_API_KEY', 'type' => 'str', 'value' => ''],
        ['group' => 'avshopguardians_main', 'name' => 'AVSHOPGUARDIANS_IGNORE_PARENT_STOCK', 'type' => 'bool', 'value' => 0],

        ['group' => 'avshopguardians_dataquality', 'name' => 'AVSHOPGUARDIANS_ONLY_PARENTS', 'type' => 'bool', 'value' => 0],
        ['group' => 'avshopguardians_dataquality', 'name' => 'AVSHOPGUARDIANS_REMOVE_PARENTS_WITHOUT_VARIANTS', 'type' => 'bool', 'value' => 0],
        ['group'  => 'avshopguardians_dataquality', 'name'  => 'AVSHOPGUARDIANS_ARTICLE_BLACKLIST','type'  => 'arr','value' => []],

        ['group'  => 'avshopguardians_sales', 'name' => 'AVSHOPGUARDIANS_OHS_DEVIATION_TRESHOLD','type' => 'str','value' => 50],
        ['group'  => 'avshopguardians_sales', 'name' => 'AVSHOPGUARDIANS_OHS_SAFETY_BUFFER_FACTOR','type' => 'str','value' => 5],
        ['group'  => 'avshopguardians_sales', 'name' => 'AVSHOPGUARDIANS_OHS_PAYMENTMETHOD_ACTIVITY_DAYS','type' => 'str','value' => 120],

    ],
    'events'      => [
        'onActivate'   => 'avshopguardians_events::onActivate',
        'onDeactivate'   => 'avshopguardians_events::onDeactivate',
    ],
];
