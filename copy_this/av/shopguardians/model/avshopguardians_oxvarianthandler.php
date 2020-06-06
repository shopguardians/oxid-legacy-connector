<?php

/**
 * Class avshopguardiansoxvarianthandler
 *
 * @author Alex Schwarz <alex.schwarz@active-value.de>
 * @copyright 2020 active value GmbH
 *
 */
class avshopguardians_oxvarianthandler extends avshopguardians_oxvarianthandler_parent
{
    /**
     * Public getter for protected method
     *
     * @inheritdoc
     */
    public function getSelections($sTitle)
    {
        return $this->_getSelections($sTitle);
    }
}