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
 * Article tab on category edit form
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Catalog_Category_Tab_Article extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('catalog_category_article');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(array('in_articles'=>1));
        }
    }

    /**
     * get current category
     *
     * @access public
     * @return Mage_Catalog_Model_Category|null
     
     */
    public function getCategory()
    {
        return Mage::registry('current_category');
    }

    /**
     * prepare the collection
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Catalog_Category_Tab_Article
     
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bialsoft_cmspro/article_collection');
        if ($this->getCategory()->getId()) {
            $constraint = 'related.category_id='.$this->getCategory()->getId();
        } else {
            $constraint = 'related.category_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bialsoft_cmspro/article_category')),
            'related.article_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * Prepare the columns
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Catalog_Category_Tab_Article
     
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_articles',
            array(
                'header_css_class'  => 'a-center',
                'type'   => 'checkbox',
                'name'   => 'in_articles',
                'values' => $this->_getSelectedArticles(),
                'align'  => 'center',
                'index'  => 'entity_id'
            )
        );
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bialsoft_cmspro')->__('Id'),
                'type'   => 'number',
                'align'  => 'left',
                'index'  => 'entity_id',
            )
        );
        $this->addColumn(
            'title',
            array(
                'header' => Mage::helper('bialsoft_cmspro')->__('Title'),
                'align'  => 'left',
                'index'  => 'title',
                'renderer' => 'bialsoft_cmspro/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/cmspro_article/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bialsoft_cmspro')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected articles
     *
     * @access protected
     * @return array
     
     */
    protected function _getSelectedArticles()
    {
        $articles = $this->getCategoryArticles();
        if (!is_array($articles)) {
            $articles = array_keys($this->getSelectedArticles());
        }
        return $articles;
    }

    /**
     * Retrieve selected articles
     *
     * @access protected
     * @return array
     
     */
    public function getSelectedArticles()
    {
        $articles = array();
        //used helper here in order not to override the category model
        $selected = Mage::helper('bialsoft_cmspro/category')->getSelectedArticles(Mage::registry('current_category'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $article) {
            $articles[$article->getId()] = array('position' => $article->getPosition());
        }
        return $articles;
    }

    /**
     * get row url
     *
     * @access public
     * @param Bialsoft_Cmspro_Model_Article
     * @return string
     
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            'adminhtml/cmspro_article_catalog_category/articlesgrid',
            array(
                'id'=>$this->getCategory()->getId()
            )
        );
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return Bialsoft_Cmspro_Block_Adminhtml_Catalog_Category_Tab_Article
     
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_articles') {
            $articleIds = $this->_getSelectedArticles();
            if (empty($articleIds)) {
                $articleIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$articleIds));
            } else {
                if ($articleIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$articleIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
