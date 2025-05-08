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
 * Article admin edit form
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Article_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'bialsoft_cmspro';
        $this->_controller = 'adminhtml_article';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('bialsoft_cmspro')->__('Save Article')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('bialsoft_cmspro')->__('Delete Article')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bialsoft_cmspro')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_article') && Mage::registry('current_article')->getId()) {
            return Mage::helper('bialsoft_cmspro')->__(
                "Edit Article '%s'",
                $this->escapeHtml(Mage::registry('current_article')->getTitle())
            );
        } else {
            return Mage::helper('bialsoft_cmspro')->__('Add Article');
        }
    }
}
