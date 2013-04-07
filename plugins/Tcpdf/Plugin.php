<?php
/**
 * Zikula SystemPlugin introducing TCPDF.
 *
 * For more information and bug-reporting, visit https://www.github.com/cmfcmf/Tcpdf.
 */
class SystemPlugin_Tcpdf_Plugin extends Zikula_AbstractPlugin implements Zikula_Plugin_ConfigurableInterface
{
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
     * @param $unit (string) User measure unit.
     * @param $format (mixed) The format used for pages.
     * @param $unicode (boolean) TRUE means that the input text is unicode (default = true)
     * @param $encoding (string) Charset encoding; default is UTF-8.
     * @param $diskcache (boolean) If TRUE reduce the RAM memory usage by caching temporary data on filesystem (slower).
     * @param $pdfa (boolean) If TRUE set the document to PDF/A mode.
     *
     * @param $langcode (string) The language to use in the pdf. (default = system language)
     *
     * @return new TCPDF()
     *
     * @note The first seven parameters are inherited from tcpdf.
     */
    public function createPdf($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false, $langcode='')
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
        if($modname !== false) {
            if(version_compare(Zikula_Core::VERSION_NUM, "1.3.5", "<="))
                $extConfigPath = "modules/$modname/lib/vendor/tcpdf_" . strToLower($modname) . "_config.php";
            else
                $extConfigPath = "modules/$modname/vendor/tcpdf_" . strToLower($modname) . "_config.php";

            //Try to include an external config file.
            if(file_exists($extConfigPath)) {
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
        $pdf->setFooterData($tc=array(0,64,0), $lc=array(0,64,128));

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
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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
     * Includes the TCPDF language file.
     *
     * @param $langcode (string) The language to use in the pdf. (default = system language)
     */
    private function includeLangFile($langcode)
    {
        if(empty($langcode)) {
            $lang = ZLanguage::getInstance();
            $langcode = $lang->getLanguageCodeLegacy();
        }

        if($langcode == 'deu') {
            $langcode = 'ger';
        }
        $langfile = DataUtil::formatForOS("plugins/Tcpdf/lib/vendor/tcpdf/config/lang/{$langcode}.php");
        if (!file_exists($langfile)) {
            $langfile = DataUtil::formatForOS('plugins/Tcpdf/lib/vendor/tcpdf/config/lang/eng.php');
        }
        require_once($langfile);
    }

    private function checkPermission()
    {
        $cacheDir = $this->baseDir . '/lib/vendor/tcpdf/cache';
        if(!is_writable($cacheDir))
            die($this->__("$cacheDir must be writeable!"));
    }
}
