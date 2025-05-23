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
 * Article comments controller
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Article_Customer_CommentController extends Mage_Core_Controller_Front_Action
{
    /**
     * Action predispatch
     * Check customer authentication for some actions
     *
     * @access public
     
     */
    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    /**
     * List comments
     *
     * @access public
     
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('bialsoft_cmspro/article_customer_comment/');
        }
        if ($block = $this->getLayout()->getBlock('article_customer_comment_list')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('My Article Comments'));

        $this->renderLayout();
    }

    /**
     * View comment
     *
     * @access public
     
     */
    public function viewAction()
    {
        $commentId = $this->getRequest()->getParam('id');
        $comment = Mage::getModel('bialsoft_cmspro/article_comment')->load($commentId);
        if (!$comment->getId() ||
            $comment->getCustomerId() != Mage::getSingleton('customer/session')->getCustomerId() ||
            $comment->getStatus() != Bialsoft_Cmspro_Model_Article_Comment::STATUS_APPROVED) {
            $this->_forward('no-route');
            return;
        }
        $article = Mage::getModel('bialsoft_cmspro/article')
            ->load($comment->getArticleId());
        if (!$article->getId() || $article->getStatus() != 1) {
            $this->_forward('no-route');
            return;
        }
        $stores = array(Mage::app()->getStore()->getId(), 0);
        if (count(array_intersect($stores, $comment->getStoreId())) == 0) {
            $this->_forward('no-route');
            return;
        }
        if (count(array_intersect($stores, $article->getStoreId())) == 0) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_comment', $comment);
        Mage::register('current_article', $article);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('bialsoft_cmspro/article_customer_comment/');
        }
        if ($block = $this->getLayout()->getBlock('customer_article_comment')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Article Comments'));
        $this->renderLayout();
    }
}
