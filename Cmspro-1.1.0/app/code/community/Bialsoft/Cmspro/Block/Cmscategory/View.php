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
 * Category view block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Cmscategory_View extends Mage_Core_Block_Template
{

    /**
     * get the current category
     *
     * @access public
     * @return mixed (Bialsoft_Cmspro_Model_Cmscategory|null)
     
     */
    public function getCurrentCmscategory()
    {
        return Mage::registry('current_cmscategory');
    }
}