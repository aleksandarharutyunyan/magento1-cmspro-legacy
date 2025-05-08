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
 * Category Articles list block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Cmscategory_Article_List extends Bialsoft_Cmspro_Block_Article_List
{

    /**
     * initialize
     *
     * @access public
     
     */
    public function __construct()
    {
        parent::__construct();
        $cmscategory = $this->getCmscategory();
        if ($cmscategory) {
            $this->getArticles()->addFieldToFilter('cmscategory_id',
                $cmscategory->getId());
        }
    }

    /**
     * get the current category
     *
     * @access public
     * @return Bialsoft_Cmspro_Model_Cmscategory
     
     */
    public function getCmscategory()
    {
        return Mage::registry('current_cmscategory');
    }
}