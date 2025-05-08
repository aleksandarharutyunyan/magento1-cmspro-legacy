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
 * Category image field renderer helper
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Article_Helper_Image extends Varien_Data_Form_Element_Image
{

    /**
     * get the url of the image
     *
     * @access protected
     * @return string
     
     */
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('bialsoft_cmspro/article_image')->getImageBaseUrl().
                $this->getValue();
        }
        return $url;
    }
}