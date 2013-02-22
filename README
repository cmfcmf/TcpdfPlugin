TCPDF plugin usage
----------------------------------------

# Overview
TCPDF plugin implements [TCPDF class for generating PDF documents](http://www.tcpdf.org/).

# Usage

## PDF generation
Simply add the two following lines of code. This will include the language files and the tcpdf config class:

    $tcpdf = PluginUtil::loadPlugin('SystemPlugin_Tcpdf_Plugin');
    $pdf = $tcpdf->createPdf(L, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

`$tcpdf->createPdf($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa)` returns `new TCPDF($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa)`
