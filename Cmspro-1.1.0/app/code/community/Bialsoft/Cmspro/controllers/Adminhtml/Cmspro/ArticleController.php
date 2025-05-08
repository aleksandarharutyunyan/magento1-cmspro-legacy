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
 * Article admin controller
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Adminhtml_Cmspro_ArticleController extends Bialsoft_Cmspro_Controller_Adminhtml_Cmspro
{

    /**
     * init the article
     *
     * @access protected
     * @return Bialsoft_Cmspro_Model_Article
     */
    protected function _initArticle()
    {
        $articleId = (int) $this->getRequest()->getParam('id');
        $article   = Mage::getModel('bialsoft_cmspro/article');
        if ($articleId) {
            $article->load($articleId);
        }
        Mage::register('current_article', $article);
        return $article;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('bialsoft_cmspro')->__('Bialsoft CMSPro'))
            ->_title(Mage::helper('bialsoft_cmspro')->__('Articles'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit article - action
     *
     * @access public
     * @return void
     
     */
    public function editAction()
    {
        $articleId = $this->getRequest()->getParam('id');
        $article   = $this->_initArticle();
        if ($articleId && !$article->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bialsoft_cmspro')->__('This article no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getArticleData(true);
        if (!empty($data)) {
            $article->setData($data);
        }
        Mage::register('article_data', $article);
        $this->loadLayout();
        $this->_title(Mage::helper('bialsoft_cmspro')->__('Bialsoft CMSPro'))
            ->_title(Mage::helper('bialsoft_cmspro')->__('Articles'));
        if ($article->getId()) {
            $this->_title($article->getTitle());
        } else {
            $this->_title(Mage::helper('bialsoft_cmspro')->__('Add article'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new article action
     *
     * @access public
     * @return void
     
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save article - action
     *
     * @access public
     * @return void
     
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('article')) {
            try {
                $article = $this->_initArticle();


                $article->addData($data);


                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $article->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }
                $categories = $this->getRequest()->getPost('category_ids', -1);
                if ($categories != -1) {
                    $categories = explode(',', $categories);
                    $categories = array_unique($categories);
                    $article->setCategoriesData($categories);
                }
                $article->save();
                $imageName = $this->_uploadAndGetName(
                    'image',
                    Mage::helper('bialsoft_cmspro/article_image')->getImageBaseDir(),
                    $data
                );
                if ($imageName) {
                    $article->setImage($imageName);
                    $article->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bialsoft_cmspro')->__('Article was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit',
                        array('id' => $article->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setArticleData($data);
                $this->_redirect('*/*/edit',
                    array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bialsoft_cmspro')->__('There was a problem saving the article.')
                );
                Mage::getSingleton('adminhtml/session')->setArticleData($data);
                $this->_redirect('*/*/edit',
                    array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bialsoft_cmspro')->__('Unable to find article to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete article - action
     *
     * @access public
     * @return void
     
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $article = Mage::getModel('bialsoft_cmspro/article');
                $article->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bialsoft_cmspro')->__('Article was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit',
                    array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bialsoft_cmspro')->__('There was an error deleting article.')
                );
                $this->_redirect('*/*/edit',
                    array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bialsoft_cmspro')->__('Could not find article to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete article - action
     *
     * @access public
     * @return void
     
     */
    public function massDeleteAction()
    {
        $articleIds = $this->getRequest()->getParam('article');
        if (!is_array($articleIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bialsoft_cmspro')->__('Please select articles to delete.')
            );
        } else {
            try {
                foreach ($articleIds as $articleId) {
                    $article = Mage::getModel('bialsoft_cmspro/article');
                    $article->setId($articleId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bialsoft_cmspro')->__('Total of %d articles were successfully deleted.',
                        count($articleIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bialsoft_cmspro')->__('There was an error deleting articles.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     
     */
    public function massStatusAction()
    {
        $articleIds = $this->getRequest()->getParam('article');
        if (!is_array($articleIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bialsoft_cmspro')->__('Please select articles.')
            );
        } else {
            try {
                foreach ($articleIds as $articleId) {
                    $article = Mage::getSingleton('bialsoft_cmspro/article')->load($articleId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d articles were successfully updated.',
                        count($articleIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bialsoft_cmspro')->__('There was an error updating articles.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass category change - action
     *
     * @access public
     * @return void
     
     */
    public function massCmscategoryIdAction()
    {
        $articleIds = $this->getRequest()->getParam('article');
        if (!is_array($articleIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bialsoft_cmspro')->__('Please select articles.')
            );
        } else {
            try {
                foreach ($articleIds as $articleId) {
                    $article = Mage::getSingleton('bialsoft_cmspro/article')->load($articleId)
                        ->setCmscategoryId($this->getRequest()->getParam('flag_cmscategory_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d articles were successfully updated.',
                        count($articleIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bialsoft_cmspro')->__('There was an error updating articles.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     
     */
    public function productsAction()
    {
        $this->_initArticle();
        $this->loadLayout();
        $this->getLayout()->getBlock('article.edit.tab.product')
            ->setArticleProducts($this->getRequest()->getPost('article_products',
                    null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     
     */
    public function productsgridAction()
    {
        $this->_initArticle();
        $this->loadLayout();
        $this->getLayout()->getBlock('article.edit.tab.product')
            ->setArticleProducts($this->getRequest()->getPost('article_products',
                    null));
        $this->renderLayout();
    }

    /**
     * get categories action
     *
     * @access public
     * @return void
     
     */
    public function categoriesAction()
    {
        $this->_initArticle();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * get child categories action
     *
     * @access public
     * @return void
     
     */
    public function categoriesJsonAction()
    {
        $this->_initArticle();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('bialsoft_cmspro/adminhtml_article_edit_tab_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     
     */
    public function exportCsvAction()
    {
        $fileName = 'article.csv';
        $content  = $this->getLayout()->createBlock('bialsoft_cmspro/adminhtml_article_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     
     */
    public function exportExcelAction()
    {
        $fileName = 'article.xls';
        $content  = $this->getLayout()->createBlock('bialsoft_cmspro/adminhtml_article_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     
     */
    public function exportXmlAction()
    {
        $fileName = 'article.xml';
        $content  = $this->getLayout()->createBlock('bialsoft_cmspro/adminhtml_article_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bialsoft_cmspro/article');
    }
}