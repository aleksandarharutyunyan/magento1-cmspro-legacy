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
 * Article edit form tab
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Article_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare the form
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Article_Edit_Tab_Form
     
     */
    protected function _prepareForm()
    {
        $form     = new Varien_Data_Form();
        $form->setHtmlIdPrefix('article_');
        $form->setFieldNameSuffix('article');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'article_form',
            array('legend' => Mage::helper('bialsoft_cmspro')->__('Article'))
        );

        $fieldset->addType(
            'image',
            Mage::getConfig()->getBlockClassName('bialsoft_cmspro/adminhtml_article_helper_image')
        );

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $values        = Mage::getResourceModel('bialsoft_cmspro/cmscategory_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="article_cmscategory_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeCmscategoryIdLink() {
                if ($(\'article_cmscategory_id\').value == \'\') {
                    $(\'article_cmscategory_id_link\').hide();
                } else {
                    $(\'article_cmscategory_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/cmspro_cmscategory/edit',
                array('id' => '{#id}', 'clear' => 1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'article_cmscategory_id\').value);
                    $(\'article_cmscategory_id_link\').href = realUrl;
                    $(\'article_cmscategory_id_link\').innerHTML = text.replace(\'{#name}\', $(\'article_cmscategory_id\').options[$(\'article_cmscategory_id\').selectedIndex].innerHTML);
                }
            }
            $(\'article_cmscategory_id\').observe(\'change\', changeCmscategoryIdLink);
            changeCmscategoryIdLink();
            </script>';

        $fieldset->addField(
            'cmscategory_id', 'select',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Category'),
            'name' => 'cmscategory_id',
            'required' => false,
            'values' => $values,
            'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'title', 'text',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Title'),
            'name' => 'title',
            'required' => true,
            'class' => 'required-entry',
            )
        );


        $fieldset->addField(
            'image', 'image',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Listing image'),
            'name' => 'image',
            )
        );


        $fieldset->addField(
            'summary', 'editor',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Summary'),
            'name' => 'summary',
            'required' => true,
            'config' => $wysiwygConfig,
            )
        );

        $fieldset->addField(
            'content', 'editor',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Content'),
            'name' => 'content',
            'config' => $wysiwygConfig,
            'required' => true,
            'class' => 'required-entry',
            )
        );

        $fieldset->addField(
            'author', 'text',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Author'),
            'name' => 'author',
            )
        );
        $fieldset->addField(
            'url_key', 'text',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Url key'),
            'name' => 'url_key',
            'note' => Mage::helper('bialsoft_cmspro')->__('Relative to Website Base URL')
            )
        );
        $fieldset->addField(
            'status', 'select',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Status'),
            'name' => 'status',
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
            'in_rss', 'select',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Show in rss'),
            'name' => 'in_rss',
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
                'store_id', 'hidden',
                array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_article')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $fieldset->addField(
            'allow_comment', 'select',
            array(
            'label' => Mage::helper('bialsoft_cmspro')->__('Allow Comments'),
            'name' => 'allow_comment',
            'values' => Mage::getModel('bialsoft_cmspro/adminhtml_source_yesnodefault')->toOptionArray()
            )
        );
        $formValues = Mage::registry('current_article')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getArticleData()) {
            $formValues = array_merge($formValues,
                Mage::getSingleton('adminhtml/session')->getArticleData());
            Mage::getSingleton('adminhtml/session')->setArticleData(null);
        } elseif (Mage::registry('current_article')) {
            $formValues = array_merge($formValues,
                Mage::registry('current_article')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}