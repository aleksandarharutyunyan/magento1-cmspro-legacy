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
class Bialsoft_Cmspro_Model_Rewrite_Sitemap_Sitemap extends Mage_Sitemap_Model_Sitemap
{

    /**
     * Generate XML file
     *
     * @return Mage_Sitemap_Model_Sitemap
     */
    public function generateXml()
    {
        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $this->getPath()));

        if ($io->fileExists($this->getSitemapFilename()) && !$io->isWriteable($this->getSitemapFilename())) {
            Mage::throwException(Mage::helper('sitemap')->__('File "%s" cannot be saved. Please, make sure the directory "%s" is writeable by web server.',
                    $this->getSitemapFilename(), $this->getPath()));
        }

        $io->streamOpen($this->getSitemapFilename());

        $io->streamWrite('<?xml version="1.0" encoding="UTF-8"?>'."\n");
        $io->streamWrite('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');

        $storeId = $this->getStoreId();
        $date    = Mage::getSingleton('core/date')->gmtDate('Y-m-d');
        $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        /**
         * Generate categories sitemap
         */
        $changefreq = (string) Mage::getStoreConfig('sitemap/category/changefreq',
                $storeId);
        $priority   = (string) Mage::getStoreConfig('sitemap/category/priority',
                $storeId);
        $collection = Mage::getResourceModel('sitemap/catalog_category')->getCollection($storeId);
        $categories = new Varien_Object();
        $categories->setItems($collection);
        Mage::dispatchEvent('sitemap_categories_generating_before',
            array(
            'collection' => $categories,
            'store_id' => $storeId
        ));
        foreach ($categories->getItems() as $item) {
            $xml = sprintf(
                '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($baseUrl.$item->getUrl()), $date, $changefreq,
                $priority
            );
            $io->streamWrite($xml);
        }
        unset($collection);

        /**
         * Generate products sitemap
         */
        $changefreq = (string) Mage::getStoreConfig('sitemap/product/changefreq',
                $storeId);
        $priority   = (string) Mage::getStoreConfig('sitemap/product/priority',
                $storeId);
        $collection = Mage::getResourceModel('sitemap/catalog_product')->getCollection($storeId);
        $products   = new Varien_Object();
        $products->setItems($collection);
        Mage::dispatchEvent('sitemap_products_generating_before',
            array(
            'collection' => $products,
            'store_id' => $storeId
        ));
        foreach ($products->getItems() as $item) {
            $xml = sprintf(
                '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($baseUrl.$item->getUrl()), $date, $changefreq,
                $priority
            );
            $io->streamWrite($xml);
        }
        unset($collection);

        /**
         * Generate cms pages sitemap
         */
        $changefreq = (string) Mage::getStoreConfig('sitemap/page/changefreq',
                $storeId);
        $priority   = (string) Mage::getStoreConfig('sitemap/page/priority',
                $storeId);
        $collection = Mage::getResourceModel('sitemap/cms_page')->getCollection($storeId);
        foreach ($collection as $item) {
            $xml = sprintf(
                '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($baseUrl.$item->getUrl()), $date, $changefreq,
                $priority
            );
            $io->streamWrite($xml);
        }
        unset($collection);







        /**
         * Generate cmscategories sitemap
         */
        $changefreq = (string) Mage::getStoreConfig('sitemap/page/changefreq',
                $storeId);
        $priority   = (string) Mage::getStoreConfig('sitemap/page/priority',
                $storeId);
        $collection = Mage::getResourceModel('bialsoft_cmspro/cmscategory_collection')
            ->addStoreFilter($storeId)
            ->addFieldToFilter('status', 1);


        foreach ($collection as $item) {
            $xml = sprintf(
                '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($item->getCmscategoryUrl()), $date,
                $changefreq, $priority
            );
            $io->streamWrite($xml);
        }
        unset($collection);













        $collection = Mage::getResourceModel('bialsoft_cmspro/article_collection')
            ->addStoreFilter($storeId)
            ->addFieldToFilter('status', 1);


        foreach ($collection as $item) {
            $xml = sprintf(
                '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($item->getArticleUrl()), $date, $changefreq,
                $priority
            );
            $io->streamWrite($xml);
        }

        unset($collection);






        $io->streamWrite('</urlset>');
        $io->streamClose();

        $this->setSitemapTime(Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s'));
        $this->save();

        return $this;
    }
}