TCPDF plugin usage
----------------------------------------

# Overview
The TCPDF plugin implements the [TCPDF class for generating PDF documents and barcodes](http://www.tcpdf.org/).

*Please note: This is still a beta version. Please submit pull requests if you have improvements or report issues!*

# Installation

1. Download the plugin from github and unzip it.
2. Put the content of the `plugins` folder in your Zikula `plugins` folder.
3. Put the content of the `themes` folder in your Zikula `themes` folder.

# Usage

## For normal users

### PDF generation

#### Link in templates to download this page as PDF
Add the `{pdfLink tag=true __text='Download as PDF'}` tag to your template. This will generate a link for downloading the current page as PDF file.

#### Direct link to download a page as PDF
Add `&theme=pdf` to any link, the page will be outputed as PDF file.

### Barcode generation
***For further parameters and examples please visit [https://github.com/cmfcmf/Tcpdf/wiki/Barcodes](https://github.com/cmfcmf/Tcpdf/wiki/Barcodes).***
- For 1D codes, add `{barcode1d code='yourCode'}` to your template.
- For 2D codes, add `{barcode2d code='yourCode'}` to your template.

## For developers

### PDF generation
Simply add the two following lines of code. This will include the language files and the TCPDF config class:

```php
$tcpdf = PluginUtil::loadPlugin('SystemPlugin_Tcpdf_Plugin');
$pdf = $tcpdf->createPdf(L, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
```

That will directly return a `new TCPDF()` object. Below you see the arguments passed to the `createPdf()` method:

```php
/**
 * Creates a new PDF file.
 *
 * @param $orientation (string) page orientation.
 * @param $unit (string) User measure unit.
 * @param $format (mixed) The format used for pages.
 * @param $unicode (boolean) TRUE means that the input text is unicode (default = true)
 * @param $encoding (string) Charset encoding; default is UTF-8.
 * @param $diskcache (boolean) If TRUE reduce the RAM memory usage by caching temporary data on filesystem (slower).
 * @param $pdfa (boolean) If TRUE set the document to PDF/A mode.
 *
 * @param $langcode (string) The language to use in the PDF. (default = system language)
 *
 * @return new TCPDF()
 *
 * @note The first seven parameters are inherited from TCPDF.
 */
```

For further documentation visit the [TCPDF documentation](http://www.TCPDF.org/doc/code/annotated.html).

### External configuration file

If you'd like to use an external configuration file, simply place it in:
- `modules/Foo/lib/vendor/tcpdf_foo_config.php` (Zikula <= 1.3.5)
- `modules/Foo/vendor/tcpdf_foo_config.php` (Zikula >= 1.3.6)

Only define values in there, which are different from the original config file.

*Example: If you'd like to change the `PDF_FONT_SIZE_MAIN` and `PDF_MARGIN_TOP`, your config file should look like this:*
```php
/**
 * custom top margin
 */
define ('PDF_MARGIN_TOP', 5);
/**
 * custom main font size
 */
define ('PDF_FONT_SIZE_MAIN', 30);
?>
```
*Please note: I chose the location looking at the News module. If you think there could be a better place or naming, please open an issue / make a pull request!*

# Contribute

Pull requests and issue-reportings are most welcome!

# License
GNU/GPLv3 (or at your option, any later version).

**Important:** This project contains the TCPDF project which is licensed under GNU/LGPLv3. You find the files under `plugins/Tcpdf/lib/vendor/tcpdf`
