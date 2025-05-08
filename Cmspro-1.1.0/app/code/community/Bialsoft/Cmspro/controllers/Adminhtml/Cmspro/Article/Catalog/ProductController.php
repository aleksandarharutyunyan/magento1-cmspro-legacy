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
 * Article - product controller
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class Bialsoft_Cmspro_Adminhtml_Cmspro_Article_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * construct
     *
     * @access protected
     * @return void
     
     */
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('Bialsoft_Cmspro');
    }

    /**
     * articles in the catalog page
     *
     * @access public
     * @return void
     
     */
    public function articlesAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.article')
            ->setProductArticles($this->getRequest()->getPost('product_articles', null));
        $this->renderLayout();
    }

    /**
     * articles grid in the catalog page
     *
     * @access public
     * @return void
     
     */
    public function articlesGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.article')
            ->setProductArticles($this->getRequest()->getPost('product_articles', null));
        $this->renderLayout();
    }
}
