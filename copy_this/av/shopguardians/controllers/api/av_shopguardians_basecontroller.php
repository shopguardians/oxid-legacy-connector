<?php

/**
 * Class avshopguardiansbasecontroller
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
abstract class av_shopguardians_basecontroller extends oxUBase
{
    /**
     * @var avshopguardians_paginationdata
     */
    public $pagination;

    public function render()
    {
        return null;
    }

    public function __construct()
    {
        parent::__construct();
        $this->handlePreflight();
    }

    /**
     * Outputs json encoded version of $data
     * Sets appropriate header and exists to omit OXIDs response
     *
     * @param $data
     */
    public function renderJson($data)
    {
        $this->checkAuth();

        $origin = $_SERVER['HTTP_ORIGIN'];

        header("Access-Control-Allow-Origin: $origin");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: x-api-key,content-type");
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }

    public function handlePreflight()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
            return;
        }
        $origin = $_SERVER['HTTP_ORIGIN'];
        header("Access-Control-Allow-Origin: $origin");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: x-api-key,content-type");
        header("Content-Type: application/json");
        exit();
    }

    /**
     * Check if the configured api key is matching the request api key
     */
    public function checkAuth()
    {
        $apiKey = avshopguardians_events::getSetting('API_KEY');

        if (!$apiKey || $_SERVER['HTTP_X_API_KEY'] != $apiKey && $_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
            avshopguardians_responsehelper::notAuthorized();
        }
    }

    protected function setPaginationParamsFromRequest()
    {
        $this->pagination = new avshopguardians_paginationdata();
        $perPage = oxRegistry::getConfig()->getRequestParameter('limit');
        if (empty($perPage)) {
            $perPage = 100;
        }
        $perPage = intval($perPage);

        $this->pagination->setPerPage($perPage);

        $page = oxRegistry::getConfig()->getRequestParameter('page');
        if (!$page) {
            $page = 0;
        }
        $page = intval($page);

        $this->pagination->setPage($page);
    }

}