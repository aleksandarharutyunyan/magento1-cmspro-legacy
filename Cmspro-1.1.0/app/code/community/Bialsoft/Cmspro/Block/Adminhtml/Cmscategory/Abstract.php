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
 * Category admin block abstract
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Cmscategory_Abstract extends Mage_Adminhtml_Block_Template
{
    /**
     * get current category
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Entity
     
     */
    public function getCmscategory()
    {
        return Mage::registry('cmscategory');
    }

    /**
     * get current category id
     *
     * @access public
     * @return int
     
     */
    public function getCmscategoryId()
    {
        if ($this->getCmscategory()) {
            return $this->getCmscategory()->getId();
        }
        return null;
    }

    /**
     * get current category Title
     *
     * @access public
     * @return string
     
     */
    public function getCmscategoryTitle()
    {
        return $this->getCmscategory()->getTitle();
    }

    /**
     * get current category path
     *
     * @access public
     * @return string
     
     */
    public function getCmscategoryPath()
    {
        if ($this->getCmscategory()) {
            return $this->getCmscategory()->getPath();
        }
        return Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId();
    }

    /**
     * check if there is a root category
     *
     * @access public
     * @return bool
     
     */
    public function hasRootCmscategory()
    {
        $root = $this->getRoot();
        if ($root && $root->getId()) {
            return true;
        }
        return false;
    }

    /**
     * get the root
     *
     * @access public
     * @param Bialsoft_Cmspro_Model_Cmscategory|null $parentNodeCmscategory
     * @param int $recursionLevel
     * @return Varien_Data_Tree_Node
     
     */
    public function getRoot($parentNodeCmscategory = null, $recursionLevel = 3)
    {
        if (!is_null($parentNodeCmscategory) && $parentNodeCmscategory->getId()) {
            return $this->getNode($parentNodeCmscategory, $recursionLevel);
        }
        $root = Mage::registry('root');
        if (is_null($root)) {
            $rootId = Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId();
            $tree = Mage::getResourceSingleton('bialsoft_cmspro/cmscategory_tree')
                ->load(null, $recursionLevel);
            if ($this->getCmscategory()) {
                $tree->loadEnsuredNodes($this->getCmscategory(), $tree->getNodeById($rootId));
            }
            $tree->addCollectionData($this->getCmscategoryCollection());
            $root = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId()) {
                $root->setTitle(Mage::helper('bialsoft_cmspro')->__('Root'));
            }
            Mage::register('root', $root);
        }
        return $root;
    }

    /**
     * Get and register categories root by specified categories IDs
     *
     * @accsess public
     * @param array $ids
     * @return Varien_Data_Tree_Node
     
     */
    public function getRootByIds($ids)
    {
        $root = Mage::registry('root');
        if (null === $root) {
            $cmscategoryTreeResource = Mage::getResourceSingleton('bialsoft_cmspro/cmscategory_tree');
            $ids     = $cmscategoryTreeResource->getExistingCmscategoryIdsBySpecifiedIds($ids);
            $tree   = $cmscategoryTreeResource->loadByIds($ids);
            $rootId = Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId();
            $root   = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId()) {
                $root->setName(Mage::helper('bialsoft_cmspro')->__('Root'));
            }
            $tree->addCollectionData($this->getCmscategoryCollection());
            Mage::register('root', $root);
        }
        return $root;
    }

    /**
     * get specific node
     *
     * @access public
     * @param Bialsoft_Cmspro_Model_Cmscategory $parentNodeCmscategory
     * @param $int $recursionLevel
     * @return Varien_Data_Tree_Node
     
     */
    public function getNode($parentNodeCmscategory, $recursionLevel = 2)
    {
        $tree = Mage::getResourceModel('bialsoft_cmspro/cmscategory_tree');
        $nodeId     = $parentNodeCmscategory->getId();
        $parentId   = $parentNodeCmscategory->getParentId();
        $node = $tree->loadNode($nodeId);
        $node->loadChildren($recursionLevel);
        if ($node && $nodeId != Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId()) {
            $node->setIsVisible(true);
        } elseif ($node && $node->getId() == Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId()) {
            $node->setTitle(Mage::helper('bialsoft_cmspro')->__('Root'));
        }
        $tree->addCollectionData($this->getCmscategoryCollection());
        return $node;
    }

    /**
     * get url for saving data
     *
     * @access public
     * @param array $args
     * @return string
     
     */
    public function getSaveUrl(array $args = array())
    {
        $params = array('_current'=>true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/save', $params);
    }

    /**
     * get url for edit
     *
     * @access public
     * @param array $args
     * @return string
     
     */
    public function getEditUrl()
    {
        return $this->getUrl(
            "*/cmspro_cmscategory/edit",
            array('_current' => true, '_query'=>false, 'id' => null, 'parent' => null)
        );
    }

    /**
     * Return root ids
     *
     * @access public
     * @return array
     
     */
    public function getRootIds()
    {
        return array(Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId());
    }
}
