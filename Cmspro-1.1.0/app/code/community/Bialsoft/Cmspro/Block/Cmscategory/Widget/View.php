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
 * Category widget block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Cmscategory_Widget_View extends Mage_Core_Block_Template implements
    Mage_Widget_Block_Interface
{
    protected $_htmlTemplate = 'bialsoft_cmspro/cmscategory/widget/view.phtml';

    /**
     * Prepare a for widget
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Cmscategory_Widget_View
     
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $cmscategoryId = $this->getData('cmscategory_id');
        if ($cmscategoryId) {
            $cmscategory = Mage::getModel('bialsoft_cmspro/cmscategory')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($cmscategoryId);
            if ($cmscategory->getStatusPath()) {
                $this->setCurrentCmscategory($cmscategory);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
