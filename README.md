TCPDF plugin usage
----------------------------------------

# Overview
The TCPDF plugin implements the [TCPDF class for generating PDF documents](http://www.tcpdf.org/).

*Please note: This is still a beta version. Please submit pull requests, if you have improvements, or report issues!*

# Installation

1. Download the plugin from github.
2. Unzip and rename it to Tcpdf.
3. Move the Tcpdf folder into the plugins folder in your Zikula root directory.
4. Go to Extensions module->System plugins and install the TCPDF plugin.

# Usage

## External configuration file

If you'd like to use an external configuration file, simply place it in:
- `modules/Foo/lib/vendor/tcpdf_foo_config.php` (Zikula <= 1.3.5)
- `modules/Foo/vendor/tcpdf_foo_config.php` (Zikula >= 1.3.6)

*Please note: I chose the location looking at the News module. If you think, there could be a better place or naming, please open an issue / make a pull request!*
## PDF generation
Simply add the two following lines of code. This will include the language files and the tcpdf config class:

    $tcpdf = PluginUtil::loadPlugin('SystemPlugin_Tcpdf_Plugin');
    $pdf = $tcpdf->createPdf(L, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

`$tcpdf->createPdf($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa)` returns `new TCPDF($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa)`

# Contribute

Pull requests and issue-reportings are most welcome!
