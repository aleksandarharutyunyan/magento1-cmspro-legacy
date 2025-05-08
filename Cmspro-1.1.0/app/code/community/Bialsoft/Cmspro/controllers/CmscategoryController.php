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
 * Category front contrller
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro

 */
class Bialsoft_Cmspro_CmscategoryController extends Mage_Core_Controller_Front_Action
{

    /**
     * default action
     *
     * @access public
     * @return void

     */
    public function indexAction()
    {

        $this->loadLayout();



        $this->_setDefaultLayout();




        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('bialsoft_cmspro/cmscategory')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                    'label' => Mage::helper('bialsoft_cmspro')->__('Home'),
                    'link' => Mage::getUrl(),
                    )
                );
                /*
                  $breadcrumbBlock->addCrumb(
                  'cmscategories',
                  array(
                  'label' => (Mage::getStoreConfig('bialsoft_cmspro/cmscategory/main_title'))
                  ? Mage::getStoreConfig('bialsoft_cmspro/cmscategory/main_title')
                  : Mage::helper('bialsoft_cmspro')->__('Categories'),
                  'link' => '',
                  )
                  );

                 */
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical',
                Mage::helper('bialsoft_cmspro/cmscategory')->getCmscategoriesUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('bialsoft_cmspro/cmscategory/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('bialsoft_cmspro/cmscategory/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('bialsoft_cmspro/cmscategory/meta_description'));
        }






        $this->renderLayout();
    }

    protected function _setDefaultLayout()
    {
        if ($rootTemplate = Mage::getStoreConfig('bialsoft_cmspro/cmscategory/default_root_template')) {
            $this->getLayout()->helper('page/layout')->applyTemplate($rootTemplate);
        }
    }

    /**
     * init Category
     *
     * @access protected
     * @return Bialsoft_Cmspro_Model_Cmscategory

     */
    protected function _initCmscategory()
    {
        $cmscategoryId = $this->getRequest()->getParam('id', 0);
        $cmscategory   = Mage::getModel('bialsoft_cmspro/cmscategory')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($cmscategoryId);
        if (!$cmscategory->getId()) {
            return false;
        } elseif (!$cmscategory->getStatus()) {
            return false;
        }
        return $cmscategory;
    }

    /**
     * view category action
     *
     * @access public
     * @return void

     */
    public function viewAction()
    {
        $cmscategory = $this->_initCmscategory();
        if (!$cmscategory) {
            $this->_forward('no-route');
            return;
        }
        if (!$cmscategory->getStatusPath()) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_cmscategory', $cmscategory);




        $this->loadLayout();


        $this->loadLayoutUpdates();
        if ($cmscategory->getLayoutUpdateXml()) {
            $this->getLayout()->getUpdate()->addUpdate($cmscategory->getLayoutUpdateXml());
            $this->generateLayoutXml()->generateLayoutBlocks();
        }
        if ($cmscategory->getRootTemplate() && $cmscategory->getRootTemplate() != 'empty') {
            $this->getLayout()->helper('page/layout')->applyTemplate($cmscategory->getRootTemplate());
        } else {
            $this->_setDefaultLayout();
        }

        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('cmspro-cmscategory cmspro-cmscategory'.$cmscategory->getId());
        }
        if (Mage::helper('bialsoft_cmspro/cmscategory')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                    'label' => Mage::helper('bialsoft_cmspro')->__('Home'),
                    'link' => Mage::getUrl(),
                    )
                );
                /*
                  $breadcrumbBlock->addCrumb(
                  'cmscategories',
                  array(
                  'label' => (Mage::getStoreConfig('bialsoft_cmspro/cmscategory/main_title'))
                  ? Mage::getStoreConfig('bialsoft_cmspro/cmscategory/main_title')
                  : Mage::helper('bialsoft_cmspro')->__('Categories'),
                  'link' => Mage::helper('bialsoft_cmspro/cmscategory')->getCmscategoriesUrl(),
                  )
                  );

                 */
                $parents = $cmscategory->getParentCmscategories();
                foreach ($parents as $parent) {
                    if ($parent->getId() != Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId()
                        &&
                        $parent->getId() != $cmscategory->getId()) {
                        $breadcrumbBlock->addCrumb(
                            'cmscategory-'.$parent->getId(),
                            array(
                            'label' => $parent->getTitle(),
                            'link' => $link   = $parent->getCmscategoryUrl(),
                            )
                        );
                    }
                }
                $breadcrumbBlock->addCrumb(
                    'cmscategory',
                    array(
                    'label' => $cmscategory->getTitle(),
                    'link' => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical',
                $cmscategory->getCmscategoryUrl());
        }
        if ($headBlock) {
            if ($cmscategory->getMetaTitle()) {
                $headBlock->setTitle($cmscategory->getMetaTitle());
            } else {
                $headBlock->setTitle($cmscategory->getTitle());
            }
            $headBlock->setKeywords($cmscategory->getMetaKeywords());
            $headBlock->setDescription($cmscategory->getMetaDescription());
        }



        $this->renderLayout();
    }

    /**
     * categories rss list action
     *
     * @access public
     * @return void

     */
    public function rssAction()
    {
        if (Mage::helper('bialsoft_cmspro/cmscategory')->isRssEnabled()) {
            $this->getResponse()->setHeader('Content-type',
                'text/xml; charset=UTF-8');
            $this->loadLayout(false);
            $this->renderLayout();
        } else {
            $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            $this->getResponse()->setHeader('Status', '404 File not found');
            $this->_forward('nofeed', 'index', 'rss');
        }
    }
}