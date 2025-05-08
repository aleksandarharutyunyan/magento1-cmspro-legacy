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
 * Article model
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Model_Article extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bialsoft_cmspro_article';
    const CACHE_TAG = 'bialsoft_cmspro_article';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bialsoft_cmspro_article';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'article';
    protected $_productInstance = null;
    protected $_categoryInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('bialsoft_cmspro/article');
    }

    /**
     * before save article
     *
     * @access protected
     * @return Bialsoft_Cmspro_Model_Article
     
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
     * get the url to the article details page
     *
     * @access public
     * @return string
     
     */
    public function getArticleUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('bialsoft_cmspro/article/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('bialsoft_cmspro/article/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('bialsoft_cmspro/article/view', array('id'=>$this->getId()));
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
     * get the article Summary
     *
     * @access public
     * @return string
     
     */
    public function getSummary()
    {
        $summary = $this->getData('summary');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($summary);
        return $html;
    }

    /**
     * get the article Content
     *
     * @access public
     * @return string
     
     */
    public function getContent()
    {
        $content = $this->getData('content');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($content);
        return $html;
    }

    /**
     * save article relation
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Article
     
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->saveArticleRelation($this);
        $this->getCategoryInstance()->saveArticleRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Article_Product
     
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('bialsoft_cmspro/article_product');
        }
        return $this->_productInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     
     */
    public function getSelectedProducts()
    {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return Bialsoft_Cmspro_Resource_Article_Product_Collection
     
     */
    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * get category relation model
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Article_Category
     
     */
    public function getCategoryInstance()
    {
        if (!$this->_categoryInstance) {
            $this->_categoryInstance = Mage::getSingleton('bialsoft_cmspro/article_category');
        }
        return $this->_categoryInstance;
    }

    /**
     * get selected categories array
     *
     * @access public
     * @return array
     
     */
    public function getSelectedCategories()
    {
        if (!$this->hasSelectedCategories()) {
            $categories = array();
            foreach ($this->getSelectedCategoriesCollection() as $category) {
                $categories[] = $category;
            }
            $this->setSelectedCategories($categories);
        }
        return $this->getData('selected_categories');
    }

    /**
     * Retrieve collection selected categories
     *
     * @access public
     * @return Bialsoft_Cmspro_Resource_Article_Category_Collection
     
     */
    public function getSelectedCategoriesCollection()
    {
        $collection = $this->getCategoryInstance()->getCategoryCollection($this);
        return $collection;
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|Bialsoft_Cmspro_Model_Cmscategory
     
     */
    public function getParentCmscategory()
    {
        if (!$this->hasData('_parent_cmscategory')) {
            if (!$this->getCmscategoryId()) {
                return null;
            } else {
                $cmscategory = Mage::getModel('bialsoft_cmspro/cmscategory')
                    ->load($this->getCmscategoryId());
                if ($cmscategory->getId()) {
                    $this->setData('_parent_cmscategory', $cmscategory);
                } else {
                    $this->setData('_parent_cmscategory', null);
                }
            }
        }
        return $this->getData('_parent_cmscategory');
    }

    /**
     * check if comments are allowed
     *
     * @access public
     * @return array
     
     */
    public function getAllowComments()
    {
        if ($this->getData('allow_comment') == Bialsoft_Cmspro_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == Bialsoft_Cmspro_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('bialsoft_cmspro/article/allow_comment');
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
        $values['allow_comment'] = Bialsoft_Cmspro_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }
    
}
