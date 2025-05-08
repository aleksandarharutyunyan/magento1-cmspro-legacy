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
 * Category admin controller
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Adminhtml_Cmspro_CmscategoryController extends Bialsoft_Cmspro_Controller_Adminhtml_Cmspro
{

    /**
     * init category
     *
     * @access protected
     * @return Bialsoft_Cmspro_Model_Cmscategory
     
     */
    protected function _initCmscategory()
    {
        $cmscategoryId = (int) $this->getRequest()->getParam('id', false);
        $cmscategory   = Mage::getModel('bialsoft_cmspro/cmscategory');
        if ($cmscategoryId) {
            $cmscategory->load($cmscategoryId);
        } else {
            $cmscategory->setData($cmscategory->getDefaultValues());
        }
        if ($activeTabId = (string) $this->getRequest()->getParam('active_tab_id')) {
            Mage::getSingleton('admin/session')->setCmscategoryActiveTabId($activeTabId);
        }
        Mage::register('cmscategory', $cmscategory);
        Mage::register('current_cmscategory', $cmscategory);
        return $cmscategory;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     
     */
    public function indexAction()
    {
        $this->_forward('edit');
    }

    /**
     * Add new category form
     *
     * @access public
     * @return void
     
     */
    public function addAction()
    {
        Mage::getSingleton('admin/session')->unsCmscategoryActiveTabId();
        $this->_forward('edit');
    }

    /**
     * Edit category page
     *
     * @access public
     * @return void
     
     */
    public function editAction()
    {
        $params['_current'] = true;
        $redirect           = false;
        $parentId           = (int) $this->getRequest()->getParam('parent');
        $cmscategoryId      = (int) $this->getRequest()->getParam('id');
        $_prevCmscategoryId = Mage::getSingleton('admin/session')->getLastEditedCmscategory(true);
        if ($_prevCmscategoryId &&
            !$this->getRequest()->getQuery('isAjax') &&
            !$this->getRequest()->getParam('clear')) {
            $this->getRequest()->setParam('id', $_prevCmscategoryId);
        }
        if ($redirect) {
            $this->_redirect('*/*/edit', $params);
            return;
        }
        if (!($cmscategory = $this->_initCmscategory())) {
            return;
        }
        $this->_title($cmscategoryId ? $cmscategory->getTitle() : $this->__('New Category'));
        $data = Mage::getSingleton('adminhtml/session')->getCmscategoryData(true);
        if (isset($data['cmscategory'])) {
            $cmscategory->addData($data['cmscategory']);
        }
        if ($this->getRequest()->getQuery('isAjax')) {
            $breadcrumbsPath = $cmscategory->getPath();
            if (empty($breadcrumbsPath)) {
                $breadcrumbsPath = Mage::getSingleton('admin/session')->getCmscategoryDeletedPath(true);
                if (!empty($breadcrumbsPath)) {
                    $breadcrumbsPath = explode('/', $breadcrumbsPath);
                    if (count($breadcrumbsPath) <= 1) {
                        $breadcrumbsPath = '';
                    } else {
                        array_pop($breadcrumbsPath);
                        $breadcrumbsPath = implode('/', $breadcrumbsPath);
                    }
                }
            }
            Mage::getSingleton('admin/session')->setLastEditedCmscategory($cmscategory->getId());
            $this->loadLayout();
            $eventResponse = new Varien_Object(
                array(
                'content' => $this->getLayout()->getBlock('cmscategory.edit')->getFormHtml().
                $this->getLayout()->getBlock('cmscategory.tree')->getBreadcrumbsJavascript(
                    $breadcrumbsPath, 'editingCmscategoryBreadcrumbs'
                ),
                'messages' => $this->getLayout()->getMessagesBlock()->getGroupedHtml(),
                )
            );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($eventResponse->getData()));
            return;
        }
        $this->loadLayout();
        $this->_title(Mage::helper('bialsoft_cmspro')->__('Bialsoft CMSPro'))
            ->_title(Mage::helper('bialsoft_cmspro')->__('Categories'));
        $this->_setActiveMenu('bialsoft_cmspro/cmscategory');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true)
            ->setContainerCssClass('cmscategory');

        $this->_addBreadcrumb(
            Mage::helper('bialsoft_cmspro')->__('Manage Categories'),
            Mage::helper('bialsoft_cmspro')->__('Manage Categories')
        );
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * Get tree node (Ajax version)
     *
     * @access public
     * @return void
     
     */
    public function cmscategoriesJsonAction()
    {
        if ($this->getRequest()->getParam('expand_all')) {
            Mage::getSingleton('admin/session')->setCmscategoryIsTreeWasExpanded(true);
        } else {
            Mage::getSingleton('admin/session')->setCmscategoryIsTreeWasExpanded(false);
        }
        if ($cmscategoryId = (int) $this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $cmscategoryId);
            if (!$cmscategory = $this->_initCmscategory()) {
                return;
            }
            $this->getResponse()->setBody(
                $this->getLayout()->createBlock('bialsoft_cmspro/adminhtml_cmscategory_tree')
                    ->getTreeJson($cmscategory)
            );
        }
    }

    /**
     * Move category action
     * @access public
     
     */
    public function moveAction()
    {
        $cmscategory = $this->_initCmscategory();
        if (!$cmscategory) {
            $this->getResponse()->setBody(
                Mage::helper('bialsoft_cmspro')->__('Category move error')
            );
            return;
        }
        $parentNodeId = $this->getRequest()->getPost('pid', false);
        $prevNodeId   = $this->getRequest()->getPost('aid', false);
        try {
            $cmscategory->move($parentNodeId, $prevNodeId);
            $this->getResponse()->setBody("SUCCESS");
        } catch (Mage_Core_Exception $e) {
            $this->getResponse()->setBody($e->getMessage());
        } catch (Exception $e) {
            $this->getResponse()->setBody(
                Mage::helper('bialsoft_cmspro')->__('Category move error')
            );
            Mage::logException($e);
        }
    }

    /**
     * Tree Action
     * Retrieve category tree
     *
     * @access public
     * @return void
     
     */
    public function treeAction()
    {
        $cmscategoryId = (int) $this->getRequest()->getParam('id');
        $cmscategory   = $this->_initCmscategory();
        $block         = $this->getLayout()->createBlock('bialsoft_cmspro/adminhtml_cmscategory_tree');
        $root          = $block->getRoot();
        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode(
                array(
                    'data' => $block->getTree(),
                    'parameters' => array(
                        'text' => $block->buildNodeName($root),
                        'draggable' => false,
                        'allowDrop' => ($root->getIsVisible()) ? true : false,
                        'id' => (int) $root->getId(),
                        'expanded' => (int) $block->getIsWasExpanded(),
                        'cmscategory_id' => (int) $cmscategory->getId(),
                        'root_visible' => (int) $root->getIsVisible()
                    )
                )
            )
        );
    }

    /**
     * Build response for refresh input element 'path' in form
     *
     * @access public
     * @return void
     
     */
    public function refreshPathAction()
    {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            $cmscategory = Mage::getModel('bialsoft_cmspro/cmscategory')->load($id);
            $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode(
                    array(
                        'id' => $id,
                        'path' => $cmscategory->getPath(),
                    )
                )
            );
        }
    }

    /**
     * Delete category action
     *
     * @access public
     * @return void
     
     */
    public function deleteAction()
    {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            try {
                $cmscategory = Mage::getModel('bialsoft_cmspro/cmscategory')->load($id);
                Mage::getSingleton('admin/session')->setCmscategoryDeletedPath($cmscategory->getPath());

                $cmscategory->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bialsoft_cmspro')->__('The category has been deleted.')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit',
                        array('_current' => true)));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bialsoft_cmspro')->__('An error occurred while trying to delete the category.')
                );
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit',
                        array('_current' => true)));
                Mage::logException($e);
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/',
                array('_current' => true, 'id' => null)));
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bialsoft_cmspro/cmscategory');
    }

    /**
     * wyisiwyg action
     *
     * @access public
     * @return void
     
     */
    public function wysiwygAction()
    {
        $elementId     = $this->getRequest()->getParam('element_id',
            md5(microtime()));
        $storeMediaUrl = Mage::app()->getStore(0)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'adminhtml/catalog_helper_form_wysiwyg_content', '',
            array(
            'editor_element_id' => $elementId,
            'store_id' => 0,
            'store_media_url' => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
    }

    /**
     * Category save action
     *
     * @access public
     * @return void
     
     */
    public function saveAction()
    {
        if (!$cmscategory = $this->_initCmscategory()) {
            return;
        }
        $refreshTree = 'false';
        if ($data        = $this->getRequest()->getPost('cmscategory')) {
            $cmscategory->addData($data);
            $imageName = $this->_uploadAndGetName(
                'image',
                Mage::helper('bialsoft_cmspro/cmscategory_image')->getImageBaseDir(),
                $data
            );
            $cmscategory->setData('image', $imageName);
            if (!$cmscategory->getId()) {
                $parentId = $this->getRequest()->getParam('parent');
                if (!$parentId) {
                    $parentId = Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategoryId();
                }
                $parentCmscategory = Mage::getModel('bialsoft_cmspro/cmscategory')->load($parentId);
                $cmscategory->setPath($parentCmscategory->getPath());
            }
            try {
                $cmscategory->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bialsoft_cmspro')->__('The category has been saved.')
                );
                $refreshTree = 'true';
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage())->setCmscategoryData($data);
                Mage::logException($e);
                $refreshTree = 'false';
            }
        }
        $url = $this->getUrl('*/*/edit',
            array('_current' => true, 'id' => $cmscategory->getId()));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("'.$url.'", {}, '.$refreshTree.');</script>'
        );
    }
}