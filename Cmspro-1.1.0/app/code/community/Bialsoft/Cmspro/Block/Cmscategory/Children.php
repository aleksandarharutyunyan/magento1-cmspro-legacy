<?php
/**
 * Bialsoft_Cmspro extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the GPL License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/gpl-license.php
 * 
 * @category       Bialsoft
 * @package        Bialsoft_Cmspro
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Category children list block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Cmscategory_Children extends Bialsoft_Cmspro_Block_Cmscategory_List
{
    /**
     * prepare the layout
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Cmscategory_Children
     
     */
    protected function _prepareLayout()
    {
        $this->getCmscategories()->addFieldToFilter('parent_id', $this->getCurrentCmscategory()->getId());
        return $this;
    }

    /**
     * get the current category
     *
     * @access protected
     * @return Bialsoft_Cmspro_Model_Cmscategory
     
     */
    public function getCurrentCmscategory()
    {
        return Mage::registry('current_cmscategory');
    }
}
