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
 * Article comment admin edit tabs
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Article_Comment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('article_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bialsoft_cmspro')->__('Article Comment'));
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
            'form_article_comment',
            array(
                'label'   => Mage::helper('bialsoft_cmspro')->__('Article comment'),
                'title'   => Mage::helper('bialsoft_cmspro')->__('Article comment'),
                'content' => $this->getLayout()->createBlock(
                    'bialsoft_cmspro/adminhtml_article_comment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_article_comment',
                array(
                    'label'   => Mage::helper('bialsoft_cmspro')->__('Store views'),
                    'title'   => Mage::helper('bialsoft_cmspro')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'bialsoft_cmspro/adminhtml_article_comment_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve comment
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Article_Comment
     
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }
}
