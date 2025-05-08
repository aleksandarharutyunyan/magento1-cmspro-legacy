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
 * Category collection resource model
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Model_Resource_Cmscategory_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('bialsoft_cmspro/cmscategory');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Add filter by store
     *
     * @access public
     * @param int|Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Collection
     
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!isset($this->_joinedFields['store'])) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }
            if (!is_array($store)) {
                $store = array($store);
            }
            if ($withAdmin) {
                $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
            }
            $this->addFilter('store', array('in' => $store), 'public');
            $this->_joinedFields['store'] = true;
        }
        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @access protected
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Collection
     
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                    array('store_table' => $this->getTable('bialsoft_cmspro/cmscategory_store')),
                    'main_table.entity_id = store_table.cmscategory_id', array()
                )
                ->group('main_table.entity_id');
            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }

    /**
     * Add Id filter
     *
     * @access public
     * @param array $cmscategoryIds
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Collection
     
     */
    public function addIdFilter($cmscategoryIds)
    {
        if (is_array($cmscategoryIds)) {
            if (empty($cmscategoryIds)) {
                $condition = '';
            } else {
                $condition = array('in' => $cmscategoryIds);
            }
        } elseif (is_numeric($cmscategoryIds)) {
            $condition = $cmscategoryIds;
        } elseif (is_string($cmscategoryIds)) {
            $ids = explode(',', $cmscategoryIds);
            if (empty($ids)) {
                $condition = $cmscategoryIds;
            } else {
                $condition = array('in' => $ids);
            }
        }
        $this->addFieldToFilter('entity_id', $condition);
        return $this;
    }

    /**
     * Add category path filter
     *
     * @access public
     * @param string $regexp
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Collection
     
     */
    public function addPathFilter($regexp)
    {
        $this->addFieldToFilter('path', array('regexp' => $regexp));
        return $this;
    }

    /**
     * Add category path filter
     *
     * @access public
     * @param array|string $paths
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Collection
     
     */
    public function addPathsFilter($paths)
    {
        if (!is_array($paths)) {
            $paths = array($paths);
        }
        $write = $this->getResource()->getWriteConnection();
        $cond  = array();
        foreach ($paths as $path) {
            $cond[] = $write->quoteInto('e.path LIKE ?', "$path%");
        }
        if ($cond) {
            $this->getSelect()->where(join(' OR ', $cond));
        }
        return $this;
    }

    /**
     * Add category level filter
     *
     * @access public
     * @param int|string $level
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Collection
     
     */
    public function addLevelFilter($level)
    {
        $this->addFieldToFilter('level', array('lteq' => $level));
        return $this;
    }

    /**
     * Add root category filter
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Collection
     */
    public function addRootLevelFilter()
    {
        $this->addFieldToFilter('path', array('neq' => '1'));
        $this->addLevelFilter(1);
        return $this;
    }

    /**
     * Add order field
     *
     * @access public
     * @param string $field
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Collection
     */
    public function addOrderField($field)
    {
        $this->setOrder($field, self::SORT_ORDER_ASC);
        return $this;
    }

    /**
     * Add active category filter
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Resource_Cmscategory_Collection
     */
    public function addStatusFilter($status = 1)
    {
        $this->addFieldToFilter('status', $status);
        return $this;
    }

    /**
     * get categories as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     
     */
    protected function _toOptionArray($valueField = 'entity_id',
                                      $labelField = 'title',
                                      $additional = array())
    {
        $res                 = array();
        $additional['value'] = $valueField;
        $additional['label'] = $labelField;

        foreach ($this as $item) {
            if ($item->getId() == Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId()) {
                continue;
            }
            foreach ($additional as $code => $field) {
                $prefix      = str_repeat('-', ($item->getData('level') - 1));
                $data[$code] = $item->getData($field);
            }
            $res[] = $data;
        }
        return $res;
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     
     */
    protected function _toOptionHash($valueField = 'entity_id',
                                     $labelField = 'title')
    {
        $res = array();
        foreach ($this as $item) {
            if ($item->getId() == Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId()) {
                continue;
            }
            $res[$item->getData($valueField)] = $item->getData($labelField);
        }
        return $res;
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}