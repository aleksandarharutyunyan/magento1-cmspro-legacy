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
 * Article list block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Article_List extends Mage_Core_Block_Template
{

    /**
     * initialize
     *
     * @access public
     
     */
    public function _construct()
    {
        parent::_construct();
        $articles = Mage::getResourceModel('bialsoft_cmspro/article_collection')
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('status', 1);
        $articles->setOrder('title', 'asc');
        $this->setArticles($articles);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Article_List
     
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
                'page/html_pager', 'bialsoft_cmspro.article.html.pager'
            )
            ->setCollection($this->getArticles());
        $this->setChild('pager', $pager);
        $this->getArticles()->load();

        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}