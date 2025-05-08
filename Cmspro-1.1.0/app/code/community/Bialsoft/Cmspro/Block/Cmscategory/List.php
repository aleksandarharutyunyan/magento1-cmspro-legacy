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
 * Category list block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Cmscategory_List extends Mage_Core_Block_Template
{

    /**
     * initialize
     *
     * @access public
     
     */
    public function _construct()
    {
        parent::_construct();
        $cmscategories = Mage::getResourceModel('bialsoft_cmspro/cmscategory_collection')
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('status', 1);
        ;
        $cmscategories->getSelect()->order('main_table.position');
        $this->setCmscategories($cmscategories);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Cmscategory_List
     
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getCmscategories()->addFieldToFilter('level', 1);
        if ($this->_getDisplayMode() == 0) {
            $pager = $this->getLayout()->createBlock(
                    'page/html_pager',
                    'bialsoft_cmspro.cmscategories.html.pager'
                )
                ->setCollection($this->getCmscategories());
            $this->setChild('pager', $pager);
            $this->getCmscategories()->load();
        }
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

    /**
     * get the display mode
     *
     * @access protected
     * @return int
     
     */
    protected function _getDisplayMode()
    {
        return Mage::getStoreConfigFlag('bialsoft_cmspro/cmscategory/tree');
    }

    /**
     * draw category
     *
     * @access public
     * @param Bialsoft_Cmspro_Model_Cmscategory
     * @param int $level
     * @return int
     
     */
    public function drawCmscategory($cmscategory, $level = 0)
    {
        $class = "";

        if (null !== Mage::registry('current_cmscategory') && Mage::registry('current_cmscategory')->getId()
            == $cmscategory->getId()) {
            $class = "active";
        }

        if (null !== Mage::registry('current_article') && Mage::registry('current_article')->getCmscategory_id()
            == $cmscategory->getId()) {
            $class = "active";
        }
        $html      = '';
        $recursion = $this->getRecursion();
        if ($recursion !== '0' && $level >= $recursion) {
            return '';
        }
        $storeIds      = Mage::getResourceSingleton(
                'bialsoft_cmspro/cmscategory'
            )
            ->lookupStoreIds($cmscategory->getId());
        $validStoreIds = array(0, Mage::app()->getStore()->getId());
        if (!array_intersect($storeIds, $validStoreIds)) {
            return '';
        }
        if (!$cmscategory->getStatus()) {
            return '';
        }
        $children       = $cmscategory->getChildrenCmscategories();
        $activeChildren = array();
        if ($recursion == 0 || $level < $recursion - 1) {
            foreach ($children as $child) {
                $childStoreIds = Mage::getResourceSingleton(
                        'bialsoft_cmspro/cmscategory'
                    )
                    ->lookupStoreIds($child->getId());
                $validStoreIds = array(0, Mage::app()->getStore()->getId());
                if (!array_intersect($childStoreIds, $validStoreIds)) {
                    continue;
                }
                if ($child->getStatus()) {
                    $activeChildren[] = $child;
                }
            }
        }
        $html .= '<li >';
        $html .= '<a class="'.$class.'" href="'.$cmscategory->getCmscategoryUrl().'">'.$cmscategory->getTitle().'</a>';
        if (count($activeChildren) > 0) {
            $html .= '<ul>';
            foreach ($children as $child) {
                $html .= $this->drawCmscategory($child, $level + 1);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }

    /**
     * get recursion
     *
     * @access public
     * @return int
     
     */
    public function getRecursion()
    {
        if (!$this->hasData('recursion')) {
            $this->setData('recursion',
                Mage::getStoreConfig('bialsoft_cmspro/cmscategory/recursion'));
        }
        return $this->getData('recursion');
    }
}