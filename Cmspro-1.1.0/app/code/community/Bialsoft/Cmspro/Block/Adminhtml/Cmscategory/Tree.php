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
 * Category admin tree block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Tree extends Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Abstract
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
        $this->setTemplate('bialsoft_cmspro/cmscategory/tree.phtml');
        $this->setUseAjax(true);
        $this->_withProductCount = true;
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Tree
     
     */
    protected function _prepareLayout()
    {
        $addUrl = $this->getUrl(
            "*/*/add",
            array(
                '_current'=>true,
                'id'=>null,
                '_query' => false
            )
        );

        $this->setChild(
            'add_sub_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label'   => Mage::helper('bialsoft_cmspro')->__('Add Child Category'),
                        'onclick' => "addNew('".$addUrl."', false)",
                        'class'   => 'add',
                        'id'      => 'add_child_cmscategory_button',
                        'style'   => $this->canAddChild() ? '' : 'display: none;'
                    )
                )
        );

        $this->setChild(
            'add_root_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label'   => Mage::helper('bialsoft_cmspro')->__('Add Root Category'),
                        'onclick' => "addNew('".$addUrl."', true)",
                        'class'   => 'add',
                        'id'      => 'add_root_cmscategory_button'
                    )
                )
        );
        return parent::_prepareLayout();
    }

    /**
     * get the category collection
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Collection
     
     */
    public function getCmscategoryCollection()
    {
        $collection = $this->getData('cmscategory_collection');
        if (is_null($collection)) {
            $collection = Mage::getModel('bialsoft_cmspro/cmscategory')->getCollection();
            $this->setData('cmscategory_collection', $collection);
        }
        return $collection;
    }

    /**
     * get html for add root button
     *
     * @access public
     * @return string
     
     */
    public function getAddRootButtonHtml()
    {
        return $this->getChildHtml('add_root_button');
    }

    /**
     * get html for add child button
     *
     * @access public
     * @return string
     
     */
    public function getAddSubButtonHtml()
    {
        return $this->getChildHtml('add_sub_button');
    }

    /**
     * get html for expand button
     *
     * @access public
     * @return string
     
     */
    public function getExpandButtonHtml()
    {
        return $this->getChildHtml('expand_button');
    }

    /**
     * get html for add collapse button
     *
     * @access public
     * @return string
     
     */
    public function getCollapseButtonHtml()
    {
        return $this->getChildHtml('collapse_button');
    }

    /**
     * get url for tree load
     *
     * @access public
     * @param mxed $expanded
     * @return string
     
     */
    public function getLoadTreeUrl($expanded=null)
    {
        $params = array('_current' => true, 'id' => null, 'store' => null);
        if ((is_null($expanded) &&
            Mage::getSingleton('admin/session')->getCmscategoryIsTreeWasExpanded()) ||
            $expanded == true) {
            $params['expand_all'] = true;
        }
        return $this->getUrl('*/*/cmscategoriesJson', $params);
    }

    /**
     * get url for loading nodes
     *
     * @access public
     * @return string
     
     */
    public function getNodesUrl()
    {
        return $this->getUrl('*/cmspro_cmscategories/jsonTree');
    }

    /**
     * check if tree is expanded
     *
     * @access public
     * @return string
     
     */
    public function getIsWasExpanded()
    {
        return Mage::getSingleton('admin/session')->getCmscategoryIsTreeWasExpanded();
    }

    /**
     * get url for moving category
     *
     * @access public
     * @return string
     
     */
    public function getMoveUrl()
    {
        return $this->getUrl('*/cmspro_cmscategory/move');
    }

    /**
     * get the tree as json
     *
     * @access public
     * @param mixed $parentNodeCmscategory
     * @return string
     
     */
    public function getTree($parentNodeCmscategory = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNodeCmscategory));
        $tree = isset($rootArray['children']) ? $rootArray['children'] : array();
        return $tree;
    }

    /**
     * get the tree as json
     *
     * @access public
     * @param mixed $parentNodeCmscategory
     * @return string
     
     */
    public function getTreeJson($parentNodeCmscategory = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNodeCmscategory));
        $json = Mage::helper('core')->jsonEncode(isset($rootArray['children']) ? $rootArray['children'] : array());
        return $json;
    }

    /**
     * Get JSON of array of categories, that are breadcrumbs for specified category path
     *
     * @access public
     * @param string $path
     * @param string $javascriptVarName
     * @return string
     
     */
    public function getBreadcrumbsJavascript($path, $javascriptVarName)
    {
        if (empty($path)) {
            return '';
        }

        $cmscategories = Mage::getResourceSingleton('bialsoft_cmspro/cmscategory_tree')
            ->loadBreadcrumbsArray($path);
        if (empty($cmscategories)) {
            return '';
        }
        foreach ($cmscategories as $key => $cmscategory) {
            $cmscategories[$key] = $this->_getNodeJson($cmscategory);
        }
        return
            '<script type="text/javascript">'
            . $javascriptVarName . ' = ' . Mage::helper('core')->jsonEncode($cmscategories) . ';'
            . ($this->canAddChild() ? '$("add_child_cmscategory_button").show();' : '$("add_child_cmscategory_button").hide();')
            . '</script>';
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
        // create a node from data array
        if (is_array($node)) {
            $node = new Varien_Data_Tree_Node($node, 'entity_id', new Varien_Data_Tree);
        }
        $item = array();
        $item['text'] = $this->buildNodeName($node);
        $item['id']   = $node->getId();
        $item['path'] = $node->getData('path');
        $item['cls']  = 'folder';
        if ($node->getStatus()) {
            $item['cls'] .= ' active-category';
        } else {
            $item['cls'] .= ' no-active-category';
        }
        $item['allowDrop'] = true;
        $item['allowDrag'] = true;
        if ((int)$node->getChildrenCount()>0) {
            $item['children'] = array();
        }
        $isParent = $this->_isParentSelectedCmscategory($node);
        if ($node->hasChildren()) {
            $item['children'] = array();
            if (!($this->getUseAjax() && $node->getLevel() > 1 && !$isParent)) {
                foreach ($node->getChildren() as $child) {
                    $item['children'][] = $this->_getNodeJson($child, $level+1);
                }
            }
        }
        if ($isParent || $node->getLevel() < 1) {
            $item['expanded'] = true;
        }
        return $item;
    }

    /**
     * Get node label
     *
     * @access public
     * @param Varien_Object $node
     * @return string
     
     */
    public function buildNodeName($node)
    {
        $result = $this->escapeHtml($node->getTitle());
        return $result;
    }

    /**
     * check if entity is movable
     *
     * @access protected
     * @param Varien_Object $node
     * @return bool
     
     */
    protected function _isCmscategoryMoveable($node)
    {
        return true;
    }

    /**
     * check if parent is selected
     *
     * @access protected
     * @param Varien_Object $node
     * @return bool
     
     */
    protected function _isParentSelectedCmscategory($node)
    {
        if ($node && $this->getCmscategory()) {
            $pathIds = $this->getCmscategory()->getPathIds();
            if (in_array($node->getId(), $pathIds)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if page loaded by outside link to category edit
     *
     * @access public
     * @return boolean
     
     */
    public function isClearEdit()
    {
        return (bool) $this->getRequest()->getParam('clear');
    }

    /**
     * Check availability of adding root category
     *
     * @access public
     * @return boolean
     
     */
    public function canAddRootCmscategory()
    {
        return true;
    }

    /**
     * Check availability of adding child category
     *
     * @access public
     * @return boolean
     */
    public function canAddChild()
    {
        return true;
    }
}
