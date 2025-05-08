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
 * Article category model
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Model_Article_Category extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     
     */
    protected function _construct()
    {
        $this->_init('bialsoft_cmspro/article_category');
    }

    /**
     * Save data for article-category relation
     *
     * @access public
     * @param  Bialsoft_Cmspro_Model_Article $article
     * @return Bialsoft_Cmspro_Model_Article_Category
     
     */
    public function saveArticleRelation($article)
    {
        $data = $article->getCategoriesData();
        if (!is_null($data)) {
            $this->_getResource()->saveArticleRelation($article, $data);
        }
        return $this;
    }

    /**
     * get categories for article
     *
     * @access public
     * @param Bialsoft_Cmspro_Model_Article $article
     * @return Bialsoft_Cmspro_Model_Resource_Article_Category_Collection
     
     */
    public function getCategoryCollection($article)
    {
        $collection = Mage::getResourceModel('bialsoft_cmspro/article_category_collection')
            ->addArticleFilter($article);
        return $collection;
    }
}
