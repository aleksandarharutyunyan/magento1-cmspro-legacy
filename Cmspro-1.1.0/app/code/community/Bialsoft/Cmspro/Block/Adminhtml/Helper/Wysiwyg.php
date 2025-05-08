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
 * Cmspro textarea attribute WYSIWYG button
 * @category   Bialsoft
 * @package    Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Helper_Wysiwyg extends Varien_Data_Form_Element_Textarea
{
    /**
     * Retrieve additional html and put it at the end of element html
     *
     * @access public
     * @return string
     
     */
    public function getAfterElementHtml()
    {
        $html = parent::getAfterElementHtml();
        $disabled = ($this->getDisabled() || $this->getReadonly());
        $html .= Mage::getSingleton('core/layout')
            ->createBlock(
                'adminhtml/widget_button',
                '',
                array(
                    'label'   => Mage::helper('catalog')->__('WYSIWYG Editor'),
                    'type'=> 'button',
                    'disabled' => $disabled,
                    'class' => ($disabled) ? 'disabled btn-wysiwyg' : 'btn-wysiwyg',
                    'onclick' => 'catalogWysiwygEditor.open(\''.
                        Mage::helper('adminhtml')->getUrl('*/*/wysiwyg').'\', \''.
                        $this->getHtmlId().'\')'
                )
            )
            ->toHtml();
        return $html;
    }
}
