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
 * Article front contrller
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_ArticleController extends Mage_Core_Controller_Front_Action
{

    /**
     * default action
     *
     * @access public
     * @return void
     
     */
    public function indexAction()
    {
        $this->_setDefaultLayout();
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('bialsoft_cmspro/article')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                    'label' => Mage::helper('bialsoft_cmspro')->__('Home'),
                    'link' => Mage::getUrl(),
                    )
                );
                /* $breadcrumbBlock->addCrumb(
                  'articles',
                  array(
                  'label' => Mage::helper('bialsoft_cmspro')->__('Articles'),
                  'link' => '',
                  )
                  ); */
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical',
                Mage::helper('bialsoft_cmspro/article')->getArticlesUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('bialsoft_cmspro/article/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('bialsoft_cmspro/article/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('bialsoft_cmspro/article/meta_description'));
        }


        $this->renderLayout();
    }

    protected function _setDefaultLayout()
    {
        if ($rootTemplate = Mage::getStoreConfig('bialsoft_cmspro/article/default_root_template')) {
            $this->getLayout()->helper('page/layout')->applyTemplate($rootTemplate);
        }
    }

    /**
     * init Article
     *
     * @access protected
     * @return Bialsoft_Cmspro_Model_Article
     
     */
    protected function _initArticle()
    {
        $articleId = $this->getRequest()->getParam('id', 0);
        $article   = Mage::getModel('bialsoft_cmspro/article')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($articleId);
        if (!$article->getId()) {
            return false;
        } elseif (!$article->getStatus()) {
            return false;
        }
        return $article;
    }

    /**
     * view article action
     *
     * @access public
     * @return void
     
     */
    public function viewAction()
    {
        $article = $this->_initArticle();
        if (!$article) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_article', $article);
        $this->loadLayout();



        if ($article->getLayoutUpdateXml()) {
            $this->getLayout()->getUpdate()->addUpdate($article->getLayoutUpdateXml());
            $this->generateLayoutXml()->generateLayoutBlocks();
        }
        if ($article->getRootTemplate() && $article->getRootTemplate() != 'empty') {
            $this->getLayout()->helper('page/layout')->applyTemplate($article->getRootTemplate());
        } else {
            $this->_setDefaultLayout();
        }

        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('cmspro-article cmspro-article'.$article->getId());
        }
        if (Mage::helper('bialsoft_cmspro/article')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                    'label' => Mage::helper('bialsoft_cmspro')->__('Home'),
                    'link' => Mage::getUrl(),
                    )
                );





                if (null !== Mage::registry('current_article') && $cmscategoryId
                    = Mage::registry('current_article')->getCmscategory_id()) {

                    $cmscategory = Mage::getModel('bialsoft_cmspro/cmscategory')
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->load($cmscategoryId);

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
                        'link' => $cmscategory->getCmscategoryUrl(),
                        )
                    );
                }





                /* $breadcrumbBlock->addCrumb(
                  'articles',
                  array(
                  'label' => Mage::helper('bialsoft_cmspro')->__('Articles'),
                  'link' => Mage::helper('bialsoft_cmspro/article')->getArticlesUrl(),
                  )
                  ); */
                $breadcrumbBlock->addCrumb(
                    'article',
                    array(
                    'label' => $article->getTitle(),
                    'link' => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $article->getArticleUrl());
        }
        if ($headBlock) {
            if ($article->getMetaTitle()) {
                $headBlock->setTitle($article->getMetaTitle());
            } else {
                $headBlock->setTitle($article->getTitle());
            }
            $headBlock->setKeywords($article->getMetaKeywords());
            $headBlock->setDescription($article->getMetaDescription());
        }
        $this->renderLayout();
    }

    /**
     * articles rss list action
     *
     * @access public
     * @return void
     
     */
    public function rssAction()
    {
        if (Mage::helper('bialsoft_cmspro/article')->isRssEnabled()) {
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

    /**
     * Submit new comment action
     * @access public
     
     */
    public function commentpostAction()
    {
        $data    = $this->getRequest()->getPost();
        $article = $this->_initArticle();
        $session = Mage::getSingleton('core/session');
        if ($article) {
            if ($article->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() ||
                    Mage::getStoreConfigFlag('bialsoft_cmspro/article/allow_guest_comment'))) {
                    $comment  = Mage::getModel('bialsoft_cmspro/article_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setArticleId($article->getId())
                                ->setStatus(Bialsoft_Cmspro_Model_Article_Comment::STATUS_PENDING)
                                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                ->setStores(array(Mage::app()->getStore()->getId()))
                                ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        } catch (Exception $e) {
                            $session->setArticleCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    } else {
                        $session->setArticleCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        } else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                } else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            } else {
                $session->addError($this->__('This article does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }
}