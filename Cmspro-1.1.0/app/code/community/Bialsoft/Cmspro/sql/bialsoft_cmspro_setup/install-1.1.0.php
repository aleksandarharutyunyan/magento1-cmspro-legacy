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
 * Cmspro module install script
 *
 * @category    Bialsoft
 * @package     Bialsoft_Cmspro
 
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bialsoft_cmspro/cmscategory'))
    ->addColumn(
        'entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Category ID'
    )
    ->addColumn(
        'title', Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
        'nullable' => false,
        ), 'Title'
    )
    ->addColumn(
        'description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(),
        'Description'
    )
    ->addColumn(
        'image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Image'
    )
    ->addColumn(
        'status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(), 'Enabled'
    )
    ->addColumn(
        'url_key', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'URL key'
    )
    ->addColumn(
        'parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'unsigned' => true,
        ), 'Parent id'
    )
    ->addColumn(
        'path', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Path'
    )
    ->addColumn(
        'position', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'unsigned' => true,
        ), 'Position'
    )
    ->addColumn(
        'level', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'unsigned' => true,
        ), 'Level'
    )
    ->addColumn(
        'children_count', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'unsigned' => true,
        ), 'Children count'
    )
    ->addColumn(
        'in_rss', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(), 'In RSS'
    )
    ->addColumn(
        'meta_title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Meta title'
    )
    ->addColumn(
        'meta_keywords', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(),
        'Meta keywords'
    )
    ->addColumn(
        'meta_description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(),
        'Meta description'
    )
    ->addColumn(
        'updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(),
        'Category Modification Time'
    )
    ->addColumn(
        'created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(),
        'Category Creation Time'
    )
    ->addColumn(
        'root_template', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(),
        'Root template'
    )
    ->addColumn(
        'layout_update_xml', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(),
        'Layout update XML'
    )
    ->setComment('Category Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bialsoft_cmspro/article'))
    ->addColumn(
        'entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Article ID'
    )
    ->addColumn(
        'cmscategory_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'unsigned' => true,
        ), 'Category ID'
    )
    ->addColumn(
        'title', Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
        'nullable' => false,
        ), 'Title'
    )
    ->addColumn(
        'image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Image'
    )
    ->addColumn(
        'summary', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(), 'Summary'
    )
    ->addColumn(
        'content', Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
        'nullable' => false,
        ), 'Content'
    )
    ->addColumn(
        'author', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Author'
    )
    ->addColumn(
        'status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(), 'Enabled'
    )
    ->addColumn(
        'url_key', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'URL key'
    )
    ->addColumn(
        'in_rss', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(), 'In RSS'
    )
    ->addColumn(
        'meta_title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Meta title'
    )
    ->addColumn(
        'meta_keywords', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(),
        'Meta keywords'
    )
    ->addColumn(
        'meta_description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(),
        'Meta description'
    )
    ->addColumn(
        'allow_comment', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(),
        'Allow Comment'
    )
    ->addColumn(
        'updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(),
        'Article Modification Time'
    )
    ->addColumn(
        'created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(),
        'Article Creation Time'
    )
    ->addColumn(
        'root_template', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(),
        'Root template'
    )
    ->addColumn(
        'layout_update_xml', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(),
        'Layout update XML'
    )
    ->addIndex($this->getIdxName('bialsoft_cmspro/cmscategory',
            array('cmscategory_id')), array('cmscategory_id'))
    ->setComment('Article Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bialsoft_cmspro/cmscategory_store'))
    ->addColumn(
        'cmscategory_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
        'nullable' => false,
        'primary' => true,
        ), 'Category ID'
    )
    ->addColumn(
        'store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Store ID'
    )
    ->addIndex(
        $this->getIdxName(
            'bialsoft_cmspro/cmscategory_store', array('store_id')
        ), array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/cmscategory_store', 'cmscategory_id',
            'bialsoft_cmspro/cmscategory', 'entity_id'
        ), 'cmscategory_id', $this->getTable('bialsoft_cmspro/cmscategory'),
        'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/cmscategory_store', 'store_id', 'core/store',
            'store_id'
        ), 'store_id', $this->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Categories To Store Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bialsoft_cmspro/article_store'))
    ->addColumn(
        'article_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
        'nullable' => false,
        'primary' => true,
        ), 'Article ID'
    )
    ->addColumn(
        'store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Store ID'
    )
    ->addIndex(
        $this->getIdxName(
            'bialsoft_cmspro/article_store', array('store_id')
        ), array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/article_store', 'article_id',
            'bialsoft_cmspro/article', 'entity_id'
        ), 'article_id', $this->getTable('bialsoft_cmspro/article'),
        'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/article_store', 'store_id', 'core/store',
            'store_id'
        ), 'store_id', $this->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Articles To Store Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bialsoft_cmspro/article_product'))
    ->addColumn(
        'rel_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Relation ID'
    )
    ->addColumn(
        'article_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Article ID'
    )
    ->addColumn(
        'product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Product ID'
    )
    ->addColumn(
        'position', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'nullable' => false,
        'default' => '0',
        ), 'Position'
    )
    ->addIndex(
        $this->getIdxName(
            'bialsoft_cmspro/article_product', array('product_id')
        ), array('product_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/article_product', 'article_id',
            'bialsoft_cmspro/article', 'entity_id'
        ), 'article_id', $this->getTable('bialsoft_cmspro/article'),
        'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/article_product', 'product_id', 'catalog/product',
            'entity_id'
        ), 'product_id', $this->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'bialsoft_cmspro/article_product',
            array('article_id', 'product_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ), array('article_id', 'product_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Article to Product Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bialsoft_cmspro/article_category'))
    ->addColumn(
        'rel_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Relation ID'
    )
    ->addColumn(
        'article_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Article ID'
    )
    ->addColumn(
        'category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Category ID'
    )
    ->addColumn(
        'position', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'nullable' => false,
        'default' => '0',
        ), 'Position'
    )
    ->addIndex(
        $this->getIdxName(
            'bialsoft_cmspro/article_category', array('category_id')
        ), array('category_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/article_category', 'article_id',
            'bialsoft_cmspro/article', 'entity_id'
        ), 'article_id', $this->getTable('bialsoft_cmspro/article'),
        'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/article_category', 'category_id',
            'catalog/category', 'entity_id'
        ), 'category_id', $this->getTable('catalog/category'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'bialsoft_cmspro/article_category',
            array('article_id', 'category_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ), array('article_id', 'category_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Article to Category Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bialsoft_cmspro/article_comment'))
    ->addColumn(
        'comment_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Article Comment ID'
    )
    ->addColumn(
        'article_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array('nullable' => false), 'Article ID'
    )
    ->addColumn(
        'title', Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array('nullable' => false), 'Comment Title'
    )
    ->addColumn(
        'comment', Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array('nullable' => false), 'Comment'
    )
    ->addColumn(
        'status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array('nullable' => false), 'Comment status'
    )
    ->addColumn(
        'customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array('nullable' => true), 'Customer id'
    )
    ->addColumn(
        'name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array('nullable' => false),
        'Customer name'
    )
    ->addColumn(
        'email', Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array('nullable' => false), 'Customer email'
    )
    ->addColumn(
        'updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(),
        'Article Comment Modification Time'
    )
    ->addColumn(
        'created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(),
        'Article Comment Creation Time'
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/article_comment', 'article_id',
            'bialsoft_cmspro/article', 'entity_id'
        ), 'article_id', $this->getTable('bialsoft_cmspro/article'),
        'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/article_comment', 'customer_id', 'customer/entity',
            'entity_id'
        ), 'customer_id', $this->getTable('customer/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Article Comments Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bialsoft_cmspro/article_comment_store'))
    ->addColumn(
        'comment_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
        'nullable' => false,
        'primary' => true,
        ), 'Comment ID'
    )
    ->addColumn(
        'store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Store ID'
    )
    ->addIndex(
        $this->getIdxName(
            'bialsoft_cmspro/article_comment_store', array('store_id')
        ), array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/article_comment_store', 'comment_id',
            'bialsoft_cmspro/article_comment', 'comment_id'
        ), 'comment_id', $this->getTable('bialsoft_cmspro/article_comment'),
        'comment_id', Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'bialsoft_cmspro/article_comment_store', 'store_id', 'core/store',
            'store_id'
        ), 'store_id', $this->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Articles Comments To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
