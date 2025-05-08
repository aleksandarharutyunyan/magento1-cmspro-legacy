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
 * Category subtree block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Cmscategory_Widget_Subtree extends Bialsoft_Cmspro_Block_Cmscategory_List implements
    Mage_Widget_Block_Interface
{
    protected $_template = 'bialsoft_cmspro/cmscategory/widget/subtree.phtml';
    /**
     * prepare the layout
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Cmscategory_Widget_Subtree
     
     */
    protected function _prepareLayout()
    {
        $this->getCmscategories()->addFieldToFilter('entity_id', $this->getCmscategoryId());
        return $this;
    }

    /**
     * get the display mode
     *
     * @access protected
     * @return int
     
     */
    protected function _getDisplayMode()
    {
        return 1;
    }

    /**
     * get the element id
     *
     * @access protected
     * @return int
     
     */
    public function getUniqueId()
    {
        if (!$this->getData('uniq_id')) {
            $this->setData('uniq_id', uniqid('subtree'));
        }
        return $this->getData('uniq_id');
    }
}
