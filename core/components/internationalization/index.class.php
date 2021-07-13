<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

abstract class InternationalizationManagerController extends modExtraManagerController
{
    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('internationalization', 'Internationalization', $this->modx->getOption('internationalization.core_path', null, $this->modx->getOption('core_path') . 'components/internationalization/') . 'model/internationalization/');

        $this->addCss($this->modx->internationalization->config['css_url'] . 'mgr/internationalization.css');

        $this->addJavascript($this->modx->internationalization->config['js_url'] . 'mgr/internationalization.js');

        $this->addJavascript($this->modx->internationalization->config['js_url'] . 'mgr/extras/extras.js');

        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                MODx.config.help_url = "' . $this->modx->internationalization->getHelpUrl() . '";
                
                Internationalization.config = ' . $this->modx->toJSON(array_merge($this->modx->internationalization->config, [
                    'branding_url'      => $this->modx->internationalization->getBrandingUrl(),
                    'branding_url_help' => $this->modx->internationalization->getHelpUrl(),
                    'record'            => $this->modx->internationalization->getInternationalizationForContext($this->getContext())
                ])) . ';
                
                MODx.perm.tree_show_resource_ids = ' . ($this->modx->hasPermission('tree_show_resource_ids') ? 1 : 0) . ';
            });
        </script>');

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getLanguageTopics()
    {
        return $this->modx->internationalization->config['lexicons'];
    }

    /**
     * @access public.
     * @returns Boolean.
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('internationalization');
    }

    /**
     * @access public.
     * @returns String.
     */
    public function getContext()
    {
        $context = $this->modx->getOption('default_context', null, 'web');

        if (isset($_GET['context'])) {
            $context = $_GET['context'];
        }

        return $context;
    }
}

class IndexManagerController extends InternationalizationManagerController
{
    /**
     * @access public.
     * @return String.
     */
    public static function getDefaultController()
    {
        return 'home';
    }
}
