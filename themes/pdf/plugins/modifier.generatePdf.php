<?php

/**
 * Generates the pdf file.
 */
function smarty_modifier_generatePdf($html)
{
    //Remove menu links
    $html = preg_replace('#<div(.*)class=\"z-menu(.*)\"(.*)>(.*)</div>#Uis', '', $html);
    $html = preg_replace('#<ul(.*)class=\"z-menulinks(.*)\"(.*)>(.*)</ul>#Uis', '', $html);
    $html = preg_replace('#<div(.*)class=\"z-statusmsg(.*)\"(.*)>(.*)</div>#Uis', '', $html);
    $html = preg_replace('#<div(.*)class=\"z-errormsg(.*)\"(.*)>(.*)</div>#Uis', '', $html);

    //Generate pdf
    $tcpdf = PluginUtil::loadPlugin('SystemPlugin_Tcpdf_Plugin');
    $pdf = $tcpdf->createPdf();
    $pdf->AddPage();
    $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html);
    $pdf->Output(PageUtil::getVar('title') . '.pdf', 'I');
}
