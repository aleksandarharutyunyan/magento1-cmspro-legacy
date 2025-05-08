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
 * Article admin edit tabs
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Article_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    /**
     * Initialize Tabs
     *
     * @access public
     
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('article_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bialsoft_cmspro')->__('Article'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Article_Edit_Tabs
     
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_article',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Article'),
            'title' => Mage::helper('bialsoft_cmspro')->__('Article'),
            'content' => $this->getLayout()->createBlock(
                    'bialsoft_cmspro/adminhtml_article_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'form_design_article',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Design'),
            'title' => Mage::helper('bialsoft_cmspro')->__('Design'),
            'content' => $this->getLayout()->createBlock(
                    'bialsoft_cmspro/adminhtml_article_edit_tab_design'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'form_meta_article',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Meta'),
            'title' => Mage::helper('bialsoft_cmspro')->__('Meta'),
            'content' => $this->getLayout()->createBlock(
                    'bialsoft_cmspro/adminhtml_article_edit_tab_meta'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_article',
                array(
                'label' => Mage::helper('bialsoft_cmspro')->__('Store views'),
                'title' => Mage::helper('bialsoft_cmspro')->__('Store views'),
                'content' => $this->getLayout()->createBlock(
                        'bialsoft_cmspro/adminhtml_article_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        $this->addTab(
            'products',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Associated products'),
            'url' => $this->getUrl('*/*/products', array('_current' => true)),
            'class' => 'ajax'
            )
        );
        $this->addTab(
            'categories',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Associated categories'),
            'url' => $this->getUrl('*/*/categories', array('_current' => true)),
            'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve article entity
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Article
     
     */
    public function getArticle()
    {
        return Mage::registry('current_article');
    }
}