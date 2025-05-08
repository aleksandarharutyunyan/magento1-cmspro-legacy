# Magento 1 CMSPro – Advanced Article & Category Management Module

**CmsPro** is a full-featured CMS module for Magento 1, designed to provide flexible and powerful article management.

## 🧩 Features

- Admin panel for managing **articles, categories, and comments**
- WYSIWYG support for article content
- Article-to-product and article-to-category relationships
- Article widgets for CMS pages and static blocks
- SEO-friendly URLs with prefix/suffix configuration
- RSS feeds, layout XML integration, and export to CSV/Excel/XML
- Frontend article and category views with templating
- Multi-store support
- Admin ACL configuration

---

## ⚙️ Compatibility

- Magento **Community Edition 1.7 – 1.9.x**

## 📁 Module Structure

The module follows Magento 1 architecture with:
- Controllers (Frontend & Adminhtml)
- Models, Resource Models, Helpers
- Admin grids/forms with tabs
- Frontend blocks and templates
- SQL install scripts and EAV attributes

---

## 🚀 Installation

Place contents of this module into your Magento root directory and run:

```
php shell/indexer.php reindexall
php bin/magento cache:flush
```

Enable from `System > Configuration > CmsPro` if necessary.

---

## 📦 Legacy Notice

This module was originally developed and sold commercially in the Magento 1 era.  
It is now released **for educational and demonstration purposes only**.  
No active support is provided.

---

## 📘 License

Released under the **MIT license** – free to use, modify and redistribute.
