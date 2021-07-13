<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once dirname(__DIR__) . '/index.class.php';

class InternationalizationHomeManagerController extends InternationalizationManagerController
{
    /**
     * @access public.
     */
    public function loadCustomCssJs()
    {
        $this->addJavascript($this->modx->internationalization->config['js_url'] . 'mgr/widgets/home.panel.js');

        $this->addJavascript($this->modx->internationalization->config['js_url'] . 'mgr/extras/actioncolumn.grid.js');
        $this->addJavascript($this->modx->internationalization->config['js_url'] . 'mgr/widgets/resources.grid.js');

        $this->addLastJavascript($this->modx->internationalization->config['js_url'] . 'mgr/sections/home.js');
    }

    /**
     * @access public.
     * @return String.
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('internationalization');
    }

    /**
     * @access public.
     * @return String.
     */
    public function getTemplateFile()
    {
        return $this->modx->internationalization->config['templates_path'] . 'home.tpl';
    }
}
