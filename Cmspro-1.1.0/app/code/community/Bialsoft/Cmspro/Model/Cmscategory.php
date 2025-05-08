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
 * Category model
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Model_Cmscategory extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bialsoft_cmspro_cmscategory';
    const CACHE_TAG = 'bialsoft_cmspro_cmscategory';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bialsoft_cmspro_cmscategory';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'cmscategory';

    /**
     * constructor
     *
     * @access public
     * @return void
     
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('bialsoft_cmspro/cmscategory');
    }

    /**
     * before save category
     *
     * @access protected
     * @return Bialsoft_Cmspro_Model_Cmscategory
     
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the category details page
     *
     * @access public
     * @return string
     
     */
    public function getCmscategoryUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('bialsoft_cmspro/cmscategory/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('bialsoft_cmspro/cmscategory/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('bialsoft_cmspro/cmscategory/view', array('id'=>$this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     
     */
    public function checkUrlKey($urlKey, $active = true)
    {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * get the category Description
     *
     * @access public
     * @return string
     
     */
    public function getDescription()
    {
        $description = $this->getData('description');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($description);
        return $html;
    }

    /**
     * save category relation
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Cmscategory
     
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Article_Collection
     
     */
    public function getSelectedArticlesCollection()
    {
        if (!$this->hasData('_article_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bialsoft_cmspro/article_collection')
                        ->addFieldToFilter('cmscategory_id', $this->getId());
                $this->setData('_article_collection', $collection);
            }
        }
        return $this->getData('_article_collection');
    }

    /**
     * get the tree model
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Tree
     
     */
    public function getTreeModel()
    {
        return Mage::getResourceModel('bialsoft_cmspro/cmscategory_tree');
    }

    /**
     * get tree model instance
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Tree
     
     */
    public function getTreeModelInstance()
    {
        if (is_null($this->_treeModel)) {
            $this->_treeModel = Mage::getResourceSingleton('bialsoft_cmspro/cmscategory_tree');
        }
        return $this->_treeModel;
    }

    /**
     * Move category
     *
     * @access public
     * @param   int $parentId new parent category id
     * @param   int $afterCmscategoryId category id after which we have put current category
     * @return  Bialsoft_Cmspro_Model_Cmscategory
     
     */
    public function move($parentId, $afterCmscategoryId)
    {
        $parent = Mage::getModel('bialsoft_cmspro/cmscategory')->load($parentId);
        if (!$parent->getId()) {
            Mage::throwException(
                Mage::helper('bialsoft_cmspro')->__(
                    'Category move operation is not possible: the new parent category was not found.'
                )
            );
        }
        if (!$this->getId()) {
            Mage::throwException(
                Mage::helper('bialsoft_cmspro')->__(
                    'Category move operation is not possible: the current category was not found.'
                )
            );
        } elseif ($parent->getId() == $this->getId()) {
            Mage::throwException(
                Mage::helper('bialsoft_cmspro')->__(
                    'Category move operation is not possible: parent category is equal to child category.'
                )
            );
        }
        $this->setMovedCmscategoryId($this->getId());
        $eventParams = array(
            $this->_eventObject => $this,
            'parent'            => $parent,
            'cmscategory_id'     => $this->getId(),
            'prev_parent_id'    => $this->getParentId(),
            'parent_id'         => $parentId
        );
        $moveComplete = false;
        $this->_getResource()->beginTransaction();
        try {
            $this->getResource()->changeParent($this, $parent, $afterCmscategoryId);
            $this->_getResource()->commit();
            $this->setAffectedCmscategoryIds(array($this->getId(), $this->getParentId(), $parentId));
            $moveComplete = true;
        } catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }
        if ($moveComplete) {
            Mage::app()->cleanCache(array(self::CACHE_TAG));
        }
        return $this;
    }

    /**
     * Get the parent category
     *
     * @access public
     * @return  Bialsoft_Cmspro_Model_Cmscategory
     
     */
    public function getParentCmscategory()
    {
        if (!$this->hasData('parent_cmscategory')) {
            $this->setData(
                'parent_cmscategory',
                Mage::getModel('bialsoft_cmspro/cmscategory')->load($this->getParentId())
            );
        }
        return $this->_getData('parent_cmscategory');
    }

    /**
     * Get the parent id
     *
     * @access public
     * @return  int
     
     */
    public function getParentId()
    {
        $parentIds = $this->getParentIds();
        return intval(array_pop($parentIds));
    }

    /**
     * Get all parent categories ids
     *
     * @access public
     * @return array
     
     */
    public function getParentIds()
    {
        return array_diff($this->getPathIds(), array($this->getId()));
    }

    /**
     * Get all categories children
     *
     * @access public
     * @param bool $asArray
     * @return mixed (array|string)
     
     */
    public function getAllChildren($asArray = false)
    {
        $children = $this->getResource()->getAllChildren($this);
        if ($asArray) {
            return $children;
        } else {
            return implode(',', $children);
        }
    }

    /**
     * Get all categories children
     *
     * @access public
     * @return string
     
     */
    public function getChildCmscategories()
    {
        return implode(',', $this->getResource()->getChildren($this, false));
    }

    /**
     * check the id
     *
     * @access public
     * @param int $id
     * @return bool
     
     */
    public function checkId($id)
    {
        return $this->_getResource()->checkId($id);
    }

    /**
     * Get array categories ids which are part of category path
     *
     * @access public
     * @return array
     
     */
    public function getPathIds()
    {
        $ids = $this->getData('path_ids');
        if (is_null($ids)) {
            $ids = explode('/', $this->getPath());
            $this->setData('path_ids', $ids);
        }
        return $ids;
    }

    /**
     * Retrieve level
     *
     * @access public
     * @return int
     
     */
    public function getLevel()
    {
        if (!$this->hasLevel()) {
            return count(explode('/', $this->getPath())) - 1;
        }
        return $this->getData('level');
    }

    /**
     * Verify category ids
     *
     * @access public
     * @param array $ids
     * @return bool
     
     */
    public function verifyIds(array $ids)
    {
        return $this->getResource()->verifyIds($ids);
    }

    /**
     * check if category has children
     *
     * @access public
     * @return bool
     
     */
    public function hasChildren()
    {
        return $this->_getResource()->getChildrenAmount($this) > 0;
    }

    /**
     * check if category can be deleted
     *
     * @access protected
     * @return Bialsoft_Cmspro_Model_Cmscategory
     
     */
    protected function _beforeDelete()
    {
        if ($this->getResource()->isForbiddenToDelete($this->getId())) {
            Mage::throwException(Mage::helper('bialsoft_cmspro')->__("Can't delete root category."));
        }
        return parent::_beforeDelete();
    }

    /**
     * get the categories
     *
     * @access public
     * @param Bialsoft_Cmspro_Model_Cmscategory $parent
     * @param int $recursionLevel
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     
     */
    public function getCmscategories($parent, $recursionLevel = 0, $sorted=false, $asCollection=false, $toLoad=true)
    {
        return $this->getResource()->getCmscategories($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
    }

    /**
     * Return parent categories of current category
     *
     * @access public
     * @return array
     
     */
    public function getParentCmscategories()
    {
        return $this->getResource()->getParentCmscategories($this);
    }

    /**
     * Return children categories of current category
     *
     * @access public
     * @return array
     
     */
    public function getChildrenCmscategories()
    {
        return $this->getResource()->getChildrenCmscategories($this);
    }

    /**
     * check if parents are enabled
     *
     * @access public
     * @return bool
     
     */
    public function getStatusPath()
    {
        $parents = $this->getParentCmscategories();
        $rootId = Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId();
        foreach ($parents as $parent) {
            if ($parent->getId() == $rootId) {
                continue;
            }
            if (!$parent->getStatus()) {
                return false;
            }
        }
        return $this->getStatus();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        $values['in_rss'] = 1;
        return $values;
    }
    
}
