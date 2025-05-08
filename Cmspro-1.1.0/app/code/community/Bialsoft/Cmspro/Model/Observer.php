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
 * Frontend observer
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 * @author      Ultimate Module Creator
 */
class Bialsoft_Cmspro_Model_Observer
{

    /**
     * add items to main menu
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return array()
     * @author Ultimate Module Creator
     */
    public function addItemsToTopmenuItems($observer)
    {
        if (Mage::getStoreConfig('bialsoft_cmspro/cmscategory/link_in_mainmenu')) {
            $menu   = $observer->getMenu();
            $tree   = $menu->getTree();
            $action = Mage::app()->getFrontController()->getAction()->getFullActionName();

            $rootcats = Mage::helper('bialsoft_cmspro/cmscategory')->getRootCmscategories();
            foreach ($rootcats as $cmscategory) {

                $cmscategoryNodeId = 'cmscategory'.$cmscategory->getId();
                $data              = array(
                    'name' => $cmscategory->getTitle(),
                    'id' => $cmscategoryNodeId,
                    'url' => $cmscategory->getCmscategoryUrl(),
                    'is_active' => ($action == 'bialsoft_cmspro_cmscategory_index'
                    || $action == 'bialsoft_cmspro_cmscategory_view')
                );
                $cmscategoryNode   = new Varien_Data_Tree_Node($data, 'id',
                    $tree, $menu);
                $menu->addChild($cmscategoryNode);

                $this->addChildItems($cmscategory, $cmscategoryNode);
            }
        }
        return $this;
    }

    private function addChildItems($cmscategory, $cmscategoryNode)
    {
        // Children menu items
        $collection = Mage::getResourceModel('bialsoft_cmspro/cmscategory_collection')
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('parent_id', $cmscategory->getId());

        $collection->getSelect()->order('main_table.position');
        if (count($collection)) {

            foreach ($collection as $scategory) {
                $tree = $cmscategoryNode->getTree();
                $data = array(
                    'name' => $scategory->getTitle(),
                    'id' => 'category-node-'.$scategory->getId(),
                    'url' => $scategory->getCmscategoryUrl(),
                );

                $subNode = new Varien_Data_Tree_Node($data, 'id', $tree,
                    $cmscategoryNode);
                $cmscategoryNode->addChild($subNode);

                $this->addChildItems($scategory, $subNode);
            }
        }
        return $this;
    }
}