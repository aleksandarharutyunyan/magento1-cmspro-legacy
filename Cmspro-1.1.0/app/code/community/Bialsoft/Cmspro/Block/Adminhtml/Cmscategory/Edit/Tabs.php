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
 * Category admin edit tabs
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    /**
     * Initialize Tabs
     *
     * @access public
     
     */
    public function __construct()
    {
        $this->setId('cmscategory_info_tabs');
        $this->setDestElementId('cmscategory_tab_content');
        $this->setTitle(Mage::helper('bialsoft_cmspro')->__('Category'));
        $this->setTemplate('widget/tabshoriz.phtml');
    }

    /**
     * Prepare Layout Content
     *
     * @access public
     * @return Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Edit_Tabs
     */
    protected function _prepareLayout()
    {
        $this->addTab(
            'form_cmscategory',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Category'),
            'title' => Mage::helper('bialsoft_cmspro')->__('Category'),
            'content' => $this->getLayout()->createBlock(
                    'bialsoft_cmspro/adminhtml_cmscategory_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'form_design_cmscategory',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Design'),
            'title' => Mage::helper('bialsoft_cmspro')->__('Designa'),
            'content' => $this->getLayout()->createBlock(
                    'bialsoft_cmspro/adminhtml_cmscategory_edit_tab_design'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'form_meta_cmscategory',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Meta'),
            'title' => Mage::helper('bialsoft_cmspro')->__('Meta'),
            'content' => $this->getLayout()->createBlock(
                    'bialsoft_cmspro/adminhtml_cmscategory_edit_tab_meta'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_cmscategory',
                array(
                'label' => Mage::helper('bialsoft_cmspro')->__('Store views'),
                'title' => Mage::helper('bialsoft_cmspro')->__('Store views'),
                'content' => $this->getLayout()->createBlock(
                        'bialsoft_cmspro/adminhtml_cmscategory_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve category entity
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Cmscategory
     
     */
    public function getCmscategory()
    {
        return Mage::registry('current_cmscategory');
    }
}