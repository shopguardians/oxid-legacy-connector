<?php
require_once __DIR__ . '/av_shopguardians_basecontroller.php';

/**
 * Class CategoryController
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class av_shopguardians_categories extends av_shopguardians_basecontroller
{
    /**
     * Returns categories with missing OXDESC, OXLONGDESC or OXTHUMB
     */
    public function getPoorlyMaintainedCategories()
    {
        $this->setPaginationParamsFromRequest();

        $aCategories = [];

        try {
            $count = avshopguardians_categoryrepository::getPoorlyMaintainedCategoriesCount();
            $this->pagination->setPagesCountFromTotalCount($count);
            $aCategories = avshopguardians_categoryrepository::getPoorlyMaintainedCategories($this->pagination);
        } catch (\Exception $e) {
            avshopguardians_responsehelper::internalServerError($e->getMessage());
        }

        $output = ['result' => avshopguardians_categorylistserializer::transform($aCategories)];

        if ($this->pagination) {
            $output['pagination'] = $this->pagination->getData();
        }

        return $this->renderJson($output);
    }
}