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
 * Adminhtml observer
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     
     */
    protected function _canAddTab($product)
    {
        if ($product->getId()) {
            return true;
        }
        if (!$product->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    /**
     * add the article tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Bialsoft_Cmspro_Model_Adminhtml_Observer
     
     */
    public function addProductArticleBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'articles',
                array(
                    'label' => Mage::helper('bialsoft_cmspro')->__('Articles'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/cmspro_article_catalog_product/articles',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save article - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Bialsoft_Cmspro_Model_Adminhtml_Observer
     
     */
    public function saveProductArticleData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('articles', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $articleProduct = Mage::getResourceSingleton('bialsoft_cmspro/article_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }
    /**
     * add the article tab to categories
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Bialsoft_Cmspro_Model_Adminhtml_Observer
     
     */
    public function addCategoryArticleBlock($observer)
    {
        $tabs = $observer->getEvent()->getTabs();
        $content = $tabs->getLayout()->createBlock(
            'bialsoft_cmspro/adminhtml_catalog_category_tab_article',
            'category.article.grid'
        )->toHtml();
        $serializer = $tabs->getLayout()->createBlock(
            'adminhtml/widget_grid_serializer',
            'category.article.grid.serializer'
        );
        $serializer->initSerializerBlock(
            'category.article.grid',
            'getSelectedArticles',
            'articles',
            'category_articles'
        );
        $serializer->addColumnInputName('position');
        $content .= $serializer->toHtml();
        $tabs->addTab(
            'article',
            array(
                'label'   => Mage::helper('bialsoft_cmspro')->__('Articles'),
                'content' => $content,
            )
        );
        return $this;
    }

    /**
     * save article - category relation
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Bialsoft_Cmspro_Model_Adminhtml_Observer
     
     */
    public function saveCategoryArticleData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('articles', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $category = Mage::registry('category');
            $articleCategory = Mage::getResourceSingleton('bialsoft_cmspro/article_category')
                ->saveCategoryRelation($category, $post);
        }
        return $this;
    }
}
