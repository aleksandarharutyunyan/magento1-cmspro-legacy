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
 * Category helper
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro

 */
class Bialsoft_Cmspro_Helper_Cmscategory extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the categories list page
     *
     * @access public
     * @return string

     */
    public function getCmscategoriesUrl()
    {
        if ($listKey = Mage::getStoreConfig('bialsoft_cmspro/cmscategory/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct' => $listKey));
        }
        return Mage::getUrl('bialsoft_cmspro/cmscategory/index');
    }

    public function getRootCmscategoryUrl()
    {
        $c = $this->getRootCmscategory();
        if ($c) {
            return $c->getCmscategoryUrl();
        }
    }

    public function getRootCmscategory()
    {
        $cmscategories = Mage::getResourceModel('bialsoft_cmspro/cmscategory_collection')
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('level', 1);

        $cmscategories->getSelect()->order('main_table.position')->limit(1);
        foreach ($cmscategories as $c) {
            return $c;
        }
    }

    public function getRootCmscategories()
    {
        $cmscategories = Mage::getResourceModel('bialsoft_cmspro/cmscategory_collection')
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('level', 1);

        $cmscategories->getSelect()->order('main_table.position');
        return $cmscategories;
    }

    public function getRootCmscategoryName()
    {
        $c = $this->getRootCmscategory();
        if ($c) {
            return $c->getTitle();
        }
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool

     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('bialsoft_cmspro/cmscategory/breadcrumbs');
    }
    const CMSCATEGORY_ROOT_ID = 1;

    /**
     * get the root id
     *
     * @access public
     * @return int

     */
    public function getRootCmscategoryId()
    {
        return self::CMSCATEGORY_ROOT_ID;
    }

    /**
     * check if the rss for category is enabled
     *
     * @access public
     * @return bool

     */
    public function isRssEnabled()
    {
        return Mage::getStoreConfigFlag('rss/config/active') &&
            Mage::getStoreConfigFlag('bialsoft_cmspro/cmscategory/rss');
    }

    /**
     * get the link to the category rss list
     *
     * @access public
     * @return string

     */
    public function getRssUrl()
    {
        return Mage::getUrl('bialsoft_cmspro/cmscategory/rss');
    }
}