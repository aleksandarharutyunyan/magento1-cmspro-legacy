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
 * related entities column renderer
 * @category   Bialsoft
 * @package    Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Helper_Column_Renderer_Relation extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    /**
     * render the column
     *
     * @access public
     * @param Varien_Object $row
     * @return string
     
     */
    public function render(Varien_Object $row)
    {
        $base = $this->getColumn()->getBaseLink();
        if (!$base) {
            return parent::render($row);
        }
        $paramsData = $this->getColumn()->getData('params');
        $params = array();
        if (is_array($paramsData)) {
            foreach ($paramsData as $name=>$getter) {
                if (is_callable(array($row, $getter))) {
                    $params[$name] = call_user_func(array($row, $getter));
                }
            }
        }
        $staticParamsData = $this->getColumn()->getData('static');
        if (is_array($staticParamsData)) {
            foreach ($staticParamsData as $key=>$value) {
                $params[$key] = $value;
            }
        }
        return '<a href="'.$this->getUrl($base, $params).'" target="_blank">'.$this->_getValue($row).'</a>';
    }
}
