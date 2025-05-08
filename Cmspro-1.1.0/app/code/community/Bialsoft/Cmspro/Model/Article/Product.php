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
 * Article product model
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Model_Article_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     
     */
    protected function _construct()
    {
        $this->_init('bialsoft_cmspro/article_product');
    }

    /**
     * Save data for article-product relation
     * @access public
     * @param  Bialsoft_Cmspro_Model_Article $article
     * @return Bialsoft_Cmspro_Model_Article_Product
     
     */
    public function saveArticleRelation($article)
    {
        $data = $article->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveArticleRelation($article, $data);
        }
        return $this;
    }

    /**
     * get products for article
     *
     * @access public
     * @param Bialsoft_Cmspro_Model_Article $article
     * @return Bialsoft_Cmspro_Model_Resource_Article_Product_Collection
     
     */
    public function getProductCollection($article)
    {
        $collection = Mage::getResourceModel('bialsoft_cmspro/article_product_collection')
            ->addArticleFilter($article);
        return $collection;
    }
}
