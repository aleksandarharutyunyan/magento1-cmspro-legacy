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
 * Admin search model
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Model_Adminhtml_Search_Article extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Adminhtml_Search_Article
     
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bialsoft_cmspro/article_collection')
            ->addFieldToFilter('title', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $article) {
            $arr[] = array(
                'id'          => 'article/1/'.$article->getId(),
                'type'        => Mage::helper('bialsoft_cmspro')->__('Article'),
                'name'        => $article->getTitle(),
                'description' => $article->getTitle(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/cmspro_article/edit',
                    array('id'=>$article->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
