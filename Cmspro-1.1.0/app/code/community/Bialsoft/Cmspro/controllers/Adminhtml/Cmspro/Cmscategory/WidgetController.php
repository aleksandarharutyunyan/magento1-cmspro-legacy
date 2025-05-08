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
 * Category admin widget controller
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Adminhtml_Cmspro_Cmscategory_WidgetController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Chooser Source action
     *
     * @access public
     * @return void
     
     */
    public function chooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $grid = $this->getLayout()->createBlock(
            'bialsoft_cmspro/adminhtml_cmscategory_widget_chooser',
            '',
            array(
                'id' => $uniqId,
            )
        );
        $this->getResponse()->setBody($grid->toHtml());
    }

    /**
     * categories json action
     *
     * @access public
     * @return void
     
     */
    public function cmscategoriesJsonAction()
    {
        if ($cmscategoryId = (int) $this->getRequest()->getPost('id')) {
            $cmscategory = Mage::getModel('bialsoft_cmspro/cmscategory')->load($cmscategoryId);
            if ($cmscategory->getId()) {
                Mage::register('cmscategory', $cmscategory);
                Mage::register('current_cmscategory', $cmscategory);
            }
            $this->getResponse()->setBody(
                $this->_getCmscategoryTreeBlock()->getTreeJson($cmscategory)
            );
        }
    }

    /**
     * get category tree block
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Widget_Chooser
     
     */
    protected function _getCmscategoryTreeBlock()
    {
        return $this->getLayout()->createBlock(
            'bialsoft_cmspro/adminhtml_cmscategory_widget_chooser',
            '',
            array(
                'id' => $this->getRequest()->getParam('uniq_id'),
                'use_massaction' => $this->getRequest()->getParam('use_massaction', false)
            )
        );
    }
}
