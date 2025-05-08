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
 * Article helper
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Helper_Article extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the articles list page
     *
     * @access public
     * @return string
     
     */
    public function getArticlesUrl()
    {
        if ($listKey = Mage::getStoreConfig('bialsoft_cmspro/article/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('bialsoft_cmspro/article/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('bialsoft_cmspro/article/breadcrumbs');
    }

    /**
     * check if the rss for article is enabled
     *
     * @access public
     * @return bool
     
     */
    public function isRssEnabled()
    {
        return  Mage::getStoreConfigFlag('rss/config/active') &&
            Mage::getStoreConfigFlag('bialsoft_cmspro/article/rss');
    }

    /**
     * get the link to the article rss list
     *
     * @access public
     * @return string
     
     */
    public function getRssUrl()
    {
        return Mage::getUrl('bialsoft_cmspro/article/rss');
    }
}
