<?php

require_once('plugins/Tcpdf/lib/vendor/tcpdf/tcpdf.php');

/**
 * Class TCPDF_Custom
 */
class TCPDFCustom extends TCPDF
{
    //Page header
    public function Header() {
        parent::Header();
    }

    // Page footer
    public function Footer() {
        parent::Footer();
    }
}