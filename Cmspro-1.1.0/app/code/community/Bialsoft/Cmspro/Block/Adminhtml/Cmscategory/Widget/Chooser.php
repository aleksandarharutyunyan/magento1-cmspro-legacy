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
 * Category admin widget chooser
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */

class Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Widget_Chooser extends Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Tree
{
    protected $_selectedCmscategories = array();

    /**
     * Block construction
     * Defines tree template and init tree params
     *
     * @access public
     * @return void
     
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('bialsoft_cmspro/cmscategory/widget/tree.phtml');
    }

    /**
     * Setter
     *
     * @access public
     * @param array $selectedCmscategories
     * @return Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Widget_Chooser
     
     */
    public function setSelectedCmscategories($selectedCmscategories)
    {
        $this->_selectedCmscategories = $selectedCmscategories;
        return $this;
    }

    /**
     * Getter
     *
     * @access public
     * @return array
     
     */
    public function getSelectedCmscategories()
    {
        return $this->_selectedCmscategories;
    }

    /**
     * Prepare chooser element HTML
     *
     * @access public
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl(
            '*/cmspro_cmscategory_widget/chooser',
            array('uniq_id' => $uniqId, 'use_massaction' => false)
        );
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);
        $value = $element->getValue();
        $cmscategoryId = false;
        if ($value) {
            $cmscategoryId = $value;
        }
        if ($cmscategoryId) {
            $label = Mage::getSingleton('bialsoft_cmspro/cmscategory')->load($cmscategoryId)
                ->getTitle();
            $chooser->setLabel($label);
        }
        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * onClick listener js function
     *
     * @access public
     * @return string
     
     */
    public function getNodeClickListener()
    {
        if ($this->getData('node_click_listener')) {
            return $this->getData('node_click_listener');
        }
        if ($this->getUseMassaction()) {
            $js = '
                function (node, e) {
                    if (node.ui.toggleCheck) {
                        node.ui.toggleCheck(true);
                    }
                }
            ';
        } else {
            $chooserJsObject = $this->getId();
            $js = '
                function (node, e) {
                    '.$chooserJsObject.'.setElementValue(node.attributes.id);
                    '.$chooserJsObject.'.setElementLabel(node.text);
                    '.$chooserJsObject.'.close();
                }
            ';
        }
        return $js;
    }

    /**
     * Get JSON of a tree node or an associative array
     *
     * @access protected
     * @param Varien_Data_Tree_Node|array $node
     * @param int $level
     * @return string
     
     */
    protected function _getNodeJson($node, $level = 0)
    {
        $item = parent::_getNodeJson($node, $level);
        if (in_array($node->getId(), $this->getSelectedCmscategories())) {
            $item['checked'] = true;
        }
        return $item;
    }

    /**
     * Tree JSON source URL
     *
     * @access public
     * @param mixed $expanded
     * @return string
     
     */
    public function getLoadTreeUrl($expanded=null)
    {
        return $this->getUrl(
            '*/cmspro_cmscategory_widget/cmscategoriesJson',
            array(
                '_current'=>true,
                'uniq_id' => $this->getId(),
                'use_massaction' => $this->getUseMassaction()
            )
        );
    }
}
