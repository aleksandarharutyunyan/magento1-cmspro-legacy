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
 * Article - product relation model
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro

 */
class Bialsoft_Cmspro_Model_Resource_Article_Product extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * initialize resource model
     *
     * @access protected
     * @see Mage_Core_Model_Resource_Abstract::_construct()

     */
    protected function _construct()
    {
        $this->_init('bialsoft_cmspro/article_product', 'rel_id');
    }

    /**
     * Save article - product relations
     *
     * @access public
     * @param Bialsoft_Cmspro_Model_Article $article
     * @param array $data
     * @return Bialsoft_Cmspro_Model_Resource_Article_Product

     */
    public function saveArticleRelation($article, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('article_id=?',
            $article->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(),
            $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                'article_id' => $article->getId(),
                'product_id' => $productId,
                'position' => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  product - article relations
     *
     * @access public
     * @param Mage_Catalog_Model_Product $prooduct
     * @param array $data
     * @return Bialsoft_Cmspro_Model_Resource_Article_Product
     */
    public function saveProductRelation($product, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('product_id=?',
            $product->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(),
            $deleteCondition);

        foreach ($data as $articleId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                'article_id' => $articleId,
                'product_id' => $product->getId(),
                'position' => @$info['position']
                )
            );
        }
        return $this;
    }
}