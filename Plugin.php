<?php
/**
 * Copyright Zikula Foundation 2012 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version.
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class SystemPlugin_Tcpdf_Plugin extends Zikula_AbstractPlugin implements Zikula_Plugin_AlwaysOnInterface
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
            'version'     => '1.0.0'
        );
    }

    /**
     * Checks plugin version and performs install/upgrade routine when needed.
     */
    public function preInitialize()
    {
        $version = $this->getVar('version', false);
        if (!$version) {
            $this->install();
        } elseif ($version !==  $this->getMetaVersion()) {
            $this->upgrade($version);
        }
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
        //Nothing to do
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
        $this->includeConfigFile();
        
        $classfile = DataUtil::formatForOS('plugins/Tcpdf/lib/vendor/tcpdf/tcpdf.php');
        require_once($classfile);
        
        $this->includeLangFile($langcode);
        
        return new TCPDF($orientation, constant($unit), constant($format), $unicode, $encoding, $diskcache, $pdfa);
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
     * Convenience Module GetVar.
     *
     * @param string  $key     Key.
     * @param boolean $default Default, false if not found.
     *
     * @return mixed
     */
    public function getVar($key, $default=false)
    {
        return ModUtil::getVar($this->getServiceId(), $key, $default);
    }
}
