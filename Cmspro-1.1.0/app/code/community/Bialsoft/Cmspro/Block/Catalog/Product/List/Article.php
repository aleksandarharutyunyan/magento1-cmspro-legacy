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
 * Article list on product page block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Catalog_Product_List_Article extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * get the list of articles
     *
     * @access protected
     * @return Bialsoft_Cmspro_Model_Resource_Article_Collection
     
     */
    public function getArticleCollection()
    {
        if (!$this->hasData('article_collection')) {
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('bialsoft_cmspro/article_collection')
                ->addStoreFilter(Mage::app()->getStore())
                ->addFieldToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('article_collection', $collection);
        }
        return $this->getData('article_collection');
    }
}
