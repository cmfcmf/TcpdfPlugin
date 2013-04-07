<?php

/**
 * Configuration class.
 */
class SystemPlugin_Tcpdf_Configuration extends Zikula_Controller_AbstractPlugin
{
    /**
     * Parent plugin instance.
     *
     * @var SystemPlugin_Tcpdf_Plugin
     */
    protected $plugin;

    protected function postInitialize()
    {
        // In this controller we don't want caching to be enabled.
        $this->view->setCaching(false);
    }

    /**
     * Fetch and render the configuration template.
     *
     * @return string The rendered template.
     */
    public function configure()
    {
        $this->getView()
            ->assign('header', ModUtil::func('Admin', 'admin', 'adminheader'))
            ->assign('footer', ModUtil::func('Admin', 'admin', 'adminfooter'));

        return $this->getView()->fetch('configuration.tpl');
    }
}