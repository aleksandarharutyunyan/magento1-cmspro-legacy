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
 * Category RSS block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro

 */
class Bialsoft_Cmspro_Block_Cmscategory_Rss extends Mage_Rss_Block_Abstract
{
    /**
     * Cache tag constant for feed reviews
     *
     * @var string
     */
    const CACHE_TAG = 'block_html_cmspro_cmscategory_rss';

    /**
     * constructor
     *
     * @access protected
     * @return void

     */
    protected function _construct()
    {
        $this->setCacheTags(array(self::CACHE_TAG));
        /*
         * setting cache to save the rss for 10 minutes
         */
        $this->setCacheKey('bialsoft_cmspro_cmscategory_rss');
        $this->setCacheLifetime(600);
    }

    /**
     * toHtml method
     *
     * @access protected
     * @return string

     */
    protected function _toHtml()
    {
        $url        = Mage::helper('bialsoft_cmspro/cmscategory')->getCmscategoriesUrl();
        $title      = (Mage::getStoreConfig('bialsoft_cmspro/cmscategory/main_title'))
                ? Mage::getStoreConfig('bialsoft_cmspro/cmscategory/main_title')
                : Mage::helper('bialsoft_cmspro')->__('Categories');
        $rssObj     = Mage::getModel('rss/rss');
        $data       = array(
            'title' => $title,
            'description' => $title,
            'link' => $url,
            'charset' => 'UTF-8',
        );
        $rssObj->_addHeader($data);
        $collection = Mage::getModel('bialsoft_cmspro/cmscategory')->getCollection()
            ->addFieldToFilter('status', 1)
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('in_rss', 1)
            ->setOrder('created_at');
        $collection->load();
        foreach ($collection as $item) {
            $description = '<p>';
            if (!$item->getStatusPath()) {
                continue;
            } $description .= '<div>'.
                Mage::helper('bialsoft_cmspro')->__('Title').': 
                '.$item->getTitle().
                '</div>';
            $description .= '<div>'.
                Mage::helper('bialsoft_cmspro')->__('Description').': 
                '.$item->getDescription().
                '</div>';
            if ($item->getImage()) {
                $description .= '<div>';
                $description .= Mage::helper('bialsoft_cmspro')->__('Image');
                $description .= '<img src="'.Mage::helper('bialsoft_cmspro/cmscategory_image')->init($item,
                        'image')->resize(75).'" alt="'.$this->escapeHtml($item->getTitle()).'" />';
                $description .= '</div>';
            }
            $description .= '</p>';
            $data = array(
                'title' => $item->getTitle(),
                'link' => $item->getCmscategoryUrl(),
                'description' => $description
            );
            $rssObj->_addEntry($data);
        }
        return $rssObj->createRssXml();
    }
}