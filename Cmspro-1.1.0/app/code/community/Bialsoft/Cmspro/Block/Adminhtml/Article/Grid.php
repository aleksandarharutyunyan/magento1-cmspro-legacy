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
 * Article admin grid block
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
class Bialsoft_Cmspro_Block_Adminhtml_Article_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('articleGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Article_Grid
     
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bialsoft_cmspro/article')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Article_Grid
     
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bialsoft_cmspro')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'cmscategory_id',
            array(
                'header'    => Mage::helper('bialsoft_cmspro')->__('Category'),
                'index'     => 'cmscategory_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bialsoft_cmspro/cmscategory_collection')
                    ->toOptionHash(),
                'renderer'  => 'bialsoft_cmspro/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getCmscategoryId'
                ),
                'static' => array(
                    'clear' => 1
                ),
                'base_link' => 'adminhtml/cmspro_cmscategory/edit'
            )
        );
        $this->addColumn(
            'title',
            array(
                'header'    => Mage::helper('bialsoft_cmspro')->__('Title'),
                'align'     => 'left',
                'index'     => 'title',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bialsoft_cmspro')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bialsoft_cmspro')->__('Enabled'),
                    '0' => Mage::helper('bialsoft_cmspro')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'author',
            array(
                'header' => Mage::helper('bialsoft_cmspro')->__('Author'),
                'index'  => 'author',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'url_key',
            array(
                'header' => Mage::helper('bialsoft_cmspro')->__('URL key'),
                'index'  => 'url_key',
            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('bialsoft_cmspro')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('bialsoft_cmspro')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('bialsoft_cmspro')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bialsoft_cmspro')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bialsoft_cmspro')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bialsoft_cmspro')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bialsoft_cmspro')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bialsoft_cmspro')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Article_Grid
     
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('article');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('bialsoft_cmspro')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('bialsoft_cmspro')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('bialsoft_cmspro')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bialsoft_cmspro')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('bialsoft_cmspro')->__('Enabled'),
                            '0' => Mage::helper('bialsoft_cmspro')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $values = Mage::getResourceModel('bialsoft_cmspro/cmscategory_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'cmscategory_id',
            array(
                'label'      => Mage::helper('bialsoft_cmspro')->__('Change Category'),
                'url'        => $this->getUrl('*/*/massCmscategoryId', array('_current'=>true)),
                'additional' => array(
                    'flag_cmscategory_id' => array(
                        'name'   => 'flag_cmscategory_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bialsoft_cmspro')->__('Category'),
                        'values' => $values
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Bialsoft_Cmspro_Model_Article
     * @return string
     
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Bialsoft_Cmspro_Block_Adminhtml_Article_Grid
     
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param Bialsoft_Cmspro_Model_Resource_Article_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Bialsoft_Cmspro_Block_Adminhtml_Article_Grid
     
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
