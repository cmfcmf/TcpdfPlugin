<?php
/**
 * Zikula SystemPlugin introducing TCPDF.
 *
 * For more information and bug-reporting, visit https://www.github.com/cmfcmf/Tcpdf.
 */
class SystemPlugin_Tcpdf_Plugin extends Zikula_AbstractPlugin implements Zikula_Plugin_ConfigurableInterface
{
    /**
     * Performs install routine.
     *
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * Performs upgrade routine.
     *
     * @param string $oldVersion
     *
     * @return bool
     */
    public function upgrade($oldVersion)
    {
        return true;
    }

    /**
     * Initialise.
     *
     * Runs at plugin init time.
     *
     * @return void
     */
    public function initialize()
    {
        //Add plugins dir
        $this->addHandlerDefinition('view.init', 'registerPlugins');
    }

    /**
     * Registers Tcpdf smarty plugins dir.
     *
     * @param Zikula_Event $event
     */
    public function registerPlugins(Zikula_Event $event)
    {
        $event->getSubject()->addPluginDir("{$this->baseDir}/templates/plugins");
    }

    /**
     * Return controller instance.
     *
     * @return Zikula_Controller_AbstractPlugin
     */
    public function getConfigurationController()
    {
        return new SystemPlugin_Tcpdf_Configuration($this->serviceManager, $this);
    }

    /**
     * Creates a new pdf file.
     *
     * @param $orientation (string) page orientation.
     * @param $unit        (string) User measure unit.
     * @param $format      (mixed) The format used for pages.
     * @param $unicode     (boolean) TRUE means that the input text is unicode (default = true)
     * @param $encoding    (string) Charset encoding; default is UTF-8.
     * @param $diskcache   (boolean) If TRUE reduce the RAM memory usage by caching temporary data on filesystem (slower).
     * @param $pdfa        (boolean) If TRUE set the document to PDF/A mode.
     *
     * @param $langcode    (string) The language to use in the pdf. (default = system language)
     *
     * @return new TCPDF()
     *
     * @note The first seven parameters are inherited from tcpdf.
     */
    public function createPdf($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false, $langcode = '')
    {
        $this->checkPermission();

        $this->includeLangFile($langcode);
        $this->includeConfigFile();

        $classfile = DataUtil::formatForOS('plugins/Tcpdf/lib/vendor/tcpdf/tcpdf.php');
        require_once($classfile);

        $pdf = new TCPDF($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        $this->setStandardConfig($pdf);

        return $pdf;
    }

    /**
     * @param        $code The code to generate the barcode of.
     * @param string $type The type of the barcode, for available types see below.
     * @param int    $width The width of *one* bar.
     * @param int    $height The height of *oen* bar.
     * @param string $color The color of the bars.
     *
     * @return string The barcode as html.
     *
     * @note It is not possible to return the barcode in other formats than html (like png / svg), because TCPDF returns them directly in the browser which destroys your page.
     *
     * Possible types:
     * - C39 : CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
     * - C39+ : CODE 39 with checksum
     * - C39E : CODE 39 EXTENDED
     * - C39E+ : CODE 39 EXTENDED + CHECKSUM
     * - C93 : CODE 93 - USS-93
     * - S25 : Standard 2 of 5
     * - S25+ : Standard 2 of 5 + CHECKSUM
     * - I25 : Interleaved 2 of 5
     * - I25+ : Interleaved 2 of 5 + CHECKSUM
     * - C128 : CODE 128
     * - C128A : CODE 128 A
     * - C128B : CODE 128 B
     * - C128C : CODE 128 C
     * - EAN2 : 2-Digits UPC-Based Extention
     * - EAN5 : 5-Digits UPC-Based Extention
     * - EAN8 : EAN 8
     * - EAN13 : EAN 13
     * - UPCA : UPC-A
     * - UPCE : UPC-E
     * - MSI : MSI (Variation of Plessey code)
     * - MSI+ : MSI + CHECKSUM (modulo 11)
     * - POSTNET : POSTNET
     * - PLANET : PLANET
     * - RMS4CC : RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)
     * - KIX : KIX (Klant index - Customer index)
     * - IMB: Intelligent Mail Barcode - Onecode - USPS-B-3200
     * - CODABAR : CODABAR
     * - CODE11 : CODE 11
     * - PHARMA : PHARMACODE
     * - PHARMA2T : PHARMACODE TWO-TRACKS
     */
    public function createBarcode1d($code, $type = 'C128', /*$format = 'html',*/ $width = 2, $height = 30, $color = 'black')
    {
        /*
        if(!isset($color)) {
            if($format != 'png') {
                $color = 'black';
            } else {
                $color = array(0, 0, 0);
            }
        }
        */
        // include 1D barcode class
        require_once('plugins/Tcpdf/lib/vendor/tcpdf/barcodes.php');

        // set the barcode content and type
        $barcode = new TCPDFBarcode($code, $type);

        //switch($format) {
        //    case 'html':
                // output the barcode as HTML object
                return $barcode->getBarcodeHTML($width, $height, $color);
        //    break;
        //    case 'png':
        //        $png = $barcode->getBarcodePNG($width, $height, $color);
        //        //return path to file
        //        return 'images/logo.gif';
        //    break;
        //}
    }

    /**
     * @param        $code The code to generate the barcode of.
     * @param string $type The type of the barcode, for available types see below.
     * @param int    $width The width of a pixel / dot.
     * @param int    $height The height of a pixel / dot.
     * @param string $color The color of the pixels / dots.
     *
     * @return string The barcode as html.
     *
     * @note It is not possible to return the barcode in other formats than html (like png / svg), because TCPDF returns them directly in the browser which destroys your page.
     *
     * Possible types:
     * - DATAMATRIX : Datamatrix (ISO/IEC 16022)
     * - PDF417 : PDF417 (ISO/IEC 15438:2006)
     * - PDF417,a,e,t,s,f,o0,o1,o2,o3,o4,o5,o6 : PDF417 with parameters: a = aspect ratio (width/height); e = error correction level (0-8); t = total number of macro segments; s = macro segment index (0-99998); f = file ID; o0 = File Name (text); o1 = Segment Count (numeric); o2 = Time Stamp (numeric); o3 = Sender (text); o4 = Addressee (text); o5 = File Size (numeric); o6 = Checksum (numeric). NOTES: Parameters t, s and f are required for a Macro Control Block, all other parametrs are optional. To use a comma character ',' on text options, replace it with the character 255: "\xff".
     * - QRCODE : QRcode Low error correction
     * - QRCODE,L : QRcode Low error correction
     * - QRCODE,M : QRcode Medium error correction
     * - QRCODE,Q : QRcode Better error correction
     * - QRCODE,H : QR-CODE Best error correction
     * - RAW: raw mode - comma-separad list of array rows
     * - RAW2: raw mode - array rows are surrounded by square parenthesis.
     * - TEST : Test matrix
     */
    public function createBarcode2d($code, $type = 'QRCODE,H', /*$format = 'html',*/ $width = 6, $height = 6, $color = 'black')
    {
        /*
        if(!isset($color)) {
            if($format != 'png') {
                $color = 'black';
            } else {
                $color = array(0, 0, 0);
            }
        }
        */
        // include 2D barcode class
        require_once('plugins/Tcpdf/lib/vendor/tcpdf/2dbarcodes.php');
        // set the barcode content and type
        $barcode = new TCPDF2DBarcode($code, $type);

        // output the barcode as HTML object
        return $barcode->getBarcodeHTML($width, $height, $color);
    }

    private function checkPermission()
    {
        $cacheDir = $this->baseDir . '/lib/vendor/tcpdf/cache';
        if (!is_writable($cacheDir)) {
            die($this->__("$cacheDir must be writeable!"));
        }
    }

    /**
     * Includes the TCPDF language file.
     *
     * @param $langcode (string) The language to use in the pdf. (default = system language)
     */
    private function includeLangFile($langcode)
    {
        if (empty($langcode)) {
            $lang = ZLanguage::getInstance();
            $langcode = $lang->getLanguageCodeLegacy();
        }

        if ($langcode == 'deu') {
            $langcode = 'ger';
        }
        $langfile = DataUtil::formatForOS("plugins/Tcpdf/lib/vendor/tcpdf/config/lang/{$langcode}.php");
        if (!file_exists($langfile)) {
            $langfile = DataUtil::formatForOS('plugins/Tcpdf/lib/vendor/tcpdf/config/lang/eng.php');
        }
        require_once($langfile);
    }

    /**
     * Includes the TCPDF config file.
     *
     * 1. It includes the plugins config file. This will be done in all cases!
     *
     * 2. It looks for a config file located in the caller module and tries to include it\n
     *    **Zikula <= 1.3.5:**
     *
     *         "modules/$modname/lib/vendor/tcpdf_" . strToLower($modname) . "_config.php"
     *
     *    **Zikula >= 1.3.6:**
     *
     *         "modules/$modname/vendor/tcpdf_" . strToLower($modname) . "_config.php"
     *
     */
    private function includeConfigFile()
    {
        //Include own config file
        $configfile = DataUtil::formatForOS("plugins/Tcpdf/lib/tcpdf_config.php");
        require_once $configfile;

        //Try to include the config file of a module
        $modname = ModUtil::getName();
        if ($modname !== false) {
            if (version_compare(Zikula_Core::VERSION_NUM, "1.3.5", "<=")) {
                $extConfigPath = "modules/$modname/lib/vendor/tcpdf_" . strToLower($modname) . "_config.php";
            } else {
                $extConfigPath = "modules/$modname/vendor/tcpdf_" . strToLower($modname) . "_config.php";
            }

            //Try to include an external config file.
            if (file_exists($extConfigPath)) {
                $configfile = DataUtil::formatForOS($extConfigPath);
                require_once $configfile;
            }

        }

        define('K_TCPDF_EXTERNAL_CONFIG', true);
    }

    private function setStandardConfig(&$pdf)
    {
        global $l; //Used by Tcpdf for language

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle(PDF_HEADER_TITLE);
        $pdf->SetSubject(PageUtil::getVar('title'));
        $pdf->SetKeywords(System::getVar('metakeywords'));

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_HEADER, '', PDF_FONT_SIZE_HEADER));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //set some language-dependent strings
        $pdf->setLanguageArray($l);

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        $pdf->SetFont(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN);
    }

    /**
     * Get plugin meta data.
     *
     * @return array Meta data.
     */
    protected function getMeta()
    {
        return array(
            'displayname' => $this->__('TCPDF'),
            'description' => $this->__('Provides the TCPDF pdf generating library'),
            'version'     => '1.0.3'
        );
    }
}
