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
 * Article comments collection resource model
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Model_Resource_Article_Comment_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('bialsoft_cmspro/article_comment');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Add filter by store
     *
     * @access public
     * @param int|Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Bialsoft_Cmspro_Model_Resource_Article_Comment_Collection
     
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
     * @return Bialsoft_Cmspro_Model_Resource_Article_Comment_Collection
     
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('bialsoft_cmspro/article_comment_store')),
                'main_table.comment_id = store_table.comment_id',
                array()
            )
            ->group('main_table.comment_id');
            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
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
