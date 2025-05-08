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
 * meta information tab
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Edit_Tab_Design extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare the form
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Edit_Tab_Meta
     
     */
    protected function _prepareForm()
    {
        $form     = new Varien_Data_Form();
        $form->setFieldNameSuffix('cmscategory');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'cmscategory_design_form',
            array('legend' => Mage::helper('bialsoft_cmspro')->__('Design'))
        );

        $fieldset->addField('root_template', 'select',
            array(
            'name' => 'root_template',
            'label' => Mage::helper('bialsoft_cmspro')->__('Layout'),
            'required' => true,
            'values' => Mage::getSingleton('page/source_layout')->toOptionArray(),
            'value' => Mage::getStoreConfig('bialsoft_cmspro/cmscategory/default_root_template'),
        ));


        $fieldset->addField('layout_update_xml', 'textarea',
            array(
            'name' => 'layout_update_xml',
            'label' => Mage::helper('bialsoft_cmspro')->__('Layout Update XML'),
            'style' => 'height:24em;',
        ));
        $form->addValues(Mage::registry('current_cmscategory')->getData());
        return parent::_prepareForm();
    }
}