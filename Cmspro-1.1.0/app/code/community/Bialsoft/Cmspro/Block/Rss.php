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
 * Cmspro RSS block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Rss extends Mage_Core_Block_Template
{
    /**
     * RSS feeds for this block
     */
    protected $_feeds = array();

    /**
     * add a new feed
     *
     * @access public
     * @param string $label
     * @param string $url
     * @param bool $prepare
     * @return Bialsoft_Cmspro_Block_Rss
     
     */
    public function addFeed($label, $url, $prepare = false)
    {
        $link = ($prepare ? $this->getUrl($url) : $url);
        $feed = new Varien_Object();
        $feed->setLabel($label);
        $feed->setUrl($link);
        $this->_feeds[$link] = $feed;
        return $this;
    }

    /**
     * get the current feeds
     *
     * @access public
     * @return array()
     
     */
    public function getFeeds()
    {
        return $this->_feeds;
    }
}
