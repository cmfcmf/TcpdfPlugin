<?php

/**
 * Generates the pdf file.
 */
function smarty_modifier_generatePdf($html)
{
    //Remove menu links
    $html = preg_replace('#<div(.*)class=\"z-menu(.*)\"(.*)>(.*)</div>#Uis', '', $html);
    
    //Delete status and error messages
    LogUtil::getStatusMessages(true);
    LogUtil::getErrorMessages(true);

    //Generate pdf
    $tcpdf = PluginUtil::loadPlugin('SystemPlugin_Tcpdf_Plugin');
    $pdf = $tcpdf->createPdf();
    $pdf->AddPage();
    $pdf->SetFont('dejavusans', '', 12, '', true);
    $pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html);
    $pdf->Output(PageUtil::getVar('title') . '.pdf', 'I');
}
