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
 * Article - category controller
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
require_once ("Mage/Adminhtml/controllers/Catalog/CategoryController.php");
class Bialsoft_Cmspro_Adminhtml_Cmspro_Article_Catalog_CategoryController extends Mage_Adminhtml_Catalog_CategoryController
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
     * articles grid in the catalog page
     *
     * @access public
     * @return void
     
     */
    public function articlesgridAction()
    {
        $this->_initCategory();
        $this->loadLayout();
        $this->getLayout()->getBlock('category.edit.tab.article')
            ->setCategoryArticles($this->getRequest()->getPost('category_articles', null));
        $this->renderLayout();
    }
}
