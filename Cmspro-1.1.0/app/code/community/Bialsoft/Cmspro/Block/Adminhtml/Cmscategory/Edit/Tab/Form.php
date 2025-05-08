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
 * Category edit form tab
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Edit_Tab_Form
     
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('cmscategory_');
        $form->setFieldNameSuffix('cmscategory');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'cmscategory_form',
            array('legend' => Mage::helper('bialsoft_cmspro')->__('Category'))
        );
        $fieldset->addType(
            'image',
            Mage::getConfig()->getBlockClassName('bialsoft_cmspro/adminhtml_cmscategory_helper_image')
        );
        $fieldset->addType(
            'editor',
            Mage::getConfig()->getBlockClassName('bialsoft_cmspro/adminhtml_helper_wysiwyg')
        );
        if (!$this->getCmscategory()->getId()) {
            $parentId = $this->getRequest()->getParam('parent');
            if (!$parentId) {
                $parentId = Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId();
            }
            $fieldset->addField(
                'path',
                'hidden',
                array(
                    'name'  => 'path',
                    'value' => $parentId
                )
            );
        } else {
            $fieldset->addField(
                'id',
                'hidden',
                array(
                    'name'  => 'id',
                    'value' => $this->getCmscategory()->getId()
                )
            );
            $fieldset->addField(
                'path',
                'hidden',
                array(
                    'name'  => 'path',
                    'value' => $this->getCmscategory()->getPath()
                )
            );
        }

        $fieldset->addField(
            'title',
            'text',
            array(
                'label' => Mage::helper('bialsoft_cmspro')->__('Title'),
                'name'  => 'title',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'description',
            'editor',
            array(
                'label' => Mage::helper('bialsoft_cmspro')->__('Description'),
                'name'  => 'description',

           )
        );

        $fieldset->addField(
            'image',
            'image',
            array(
                'label' => Mage::helper('bialsoft_cmspro')->__('Image'),
                'name'  => 'image',

           )
        );
        $fieldset->addField(
            'url_key',
            'text',
            array(
                'label' => Mage::helper('bialsoft_cmspro')->__('Url key'),
                'name'  => 'url_key',
                'note'  => Mage::helper('bialsoft_cmspro')->__('Relative to Website Base URL')
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bialsoft_cmspro')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bialsoft_cmspro')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bialsoft_cmspro')->__('Disabled'),
                    ),
                ),
            )
        );
        $fieldset->addField(
            'in_rss',
            'select',
            array(
                'label'  => Mage::helper('bialsoft_cmspro')->__('Show in rss'),
                'name'   => 'in_rss',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bialsoft_cmspro')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bialsoft_cmspro')->__('No'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_cmscategory')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $form->addValues($this->getCmscategory()->getData());
        return parent::_prepareForm();
    }

    /**
     * get the current category
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Cmscategory
     */
    public function getCmscategory()
    {
        return Mage::registry('cmscategory');
    }
}
