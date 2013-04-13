<?php

/**
 * Generates the pdf file.
 */
function smarty_modifier_generatePdf($html)
{
    //Remove menu links
    $html = preg_replace('#<div(.*)class=\"z-menu(.*)\"(.*)>(.*)</div>#Uis', '', $html);
    $html = preg_replace('#<ul(.*)class=\"z-menulinks(.*)\"(.*)>(.*)</ul>#Uis', '', $html);
    
    //Remove LogUtils
    $html = preg_replace('#<div(.*)class=\"z-statusmsg(.*)\"(.*)>(.*)</div>#Uis', '', $html);
    $html = preg_replace('#<div(.*)class=\"z-errormsg(.*)\"(.*)>(.*)</div>#Uis', '', $html);

    //Split html by barcodes to properly generate them:
    $htmlParts = preg_split("#!--TCPDFBARCODE dimension='(.*)' code='(.*)' type='(.*)' width='(.*)' height='(.*)' color='(.*)'--!#Uis", $html);
    $barcodes = preg_match_all("#!--TCPDFBARCODE dimension='(.*)' code='(.*)' type='(.*)' width='(.*)' height='(.*)' color='(.*)'--!#Uis", $html, $matches);
    
    //barcode style
    $barcodeStyle = array(
        'position' => '',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => true,
        'hpadding' => 1,
        'vpadding' => 1,
        'fgcolor' => array(0,0,0),
        'bgcolor' => false, //array(255,255,255),
        'text' => false,
        'font' => 'helvetica',
        'fontsize' => 8,
        'stretchtext' => 4,
        'module_width' => 1, // width of a single module in points
        'module_height' => 1 // height of a single module in points
    );
    
    //Generate pdf
    $tcpdf = PluginUtil::loadPlugin('SystemPlugin_Tcpdf_Plugin');
    
    $pdf = $tcpdf->createPdf();
    $pdf->AddPage();
    foreach($htmlParts as $key => $htmlPart) {
        $pdf->writeHTML($htmlPart);
        if(isset($matches[$key])) {
            $barcodeStyle['fgcolor'] = $pdf->convertHTMLColorToDec($matches[6][$key]);
            if($matches[1][$key] == '1D') {
                $pdf->write1DBarcode($matches[2][$key], $matches[3][$key], '', '', '', $matches[4][$key] * 5, $matches[5][$key] * 10, $barcodeStyle, 'N');
            } else {
                $pdf->write2DBarcode($matches[2][$key], $matches[3][$key], $pdf->GetX(), $pdf->GetY(), $matches[4][$key] * 10, $matches[5][$key] * 10, $barcodeStyle, 'N');
            }
        }
    }

    $pdf->Output(PageUtil::getVar('title') . '.pdf', 'I');
}
