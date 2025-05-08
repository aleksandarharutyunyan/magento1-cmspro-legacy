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
 * Category helper
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Helper_Category extends Bialsoft_Cmspro_Helper_Data
{

    /**
     * get the selected articles for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return array()
     
     */
    public function getSelectedArticles(Mage_Catalog_Model_Category $category)
    {
        if (!$category->hasSelectedArticles()) {
            $articles = array();
            foreach ($this->getSelectedArticlesCollection($category) as $article) {
                $articles[] = $article;
            }
            $category->setSelectedArticles($articles);
        }
        return $category->getData('selected_articles');
    }

    /**
     * get article collection for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return Bialsoft_Cmspro_Model_Resource_Article_Collection
     
     */
    public function getSelectedArticlesCollection(Mage_Catalog_Model_Category $category)
    {
        $collection = Mage::getResourceSingleton('bialsoft_cmspro/article_collection')
            ->addCategoryFilter($category);
        return $collection;
    }
}
