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
 * Product helper
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Helper_Product extends Bialsoft_Cmspro_Helper_Data
{

    /**
     * get the selected articles for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     
     */
    public function getSelectedArticles(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedArticles()) {
            $articles = array();
            foreach ($this->getSelectedArticlesCollection($product) as $article) {
                $articles[] = $article;
            }
            $product->setSelectedArticles($articles);
        }
        return $product->getData('selected_articles');
    }

    /**
     * get article collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return Bialsoft_Cmspro_Model_Resource_Article_Collection
     
     */
    public function getSelectedArticlesCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('bialsoft_cmspro/article_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
