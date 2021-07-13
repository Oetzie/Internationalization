<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once __DIR__ . '/internationalization.class.php';

class InternationalizationPlugins extends Internationalization
{
    /**
     * @access public.
     * @param Array $properties.
     */
    public function onLoadWebDocument(array $properties = [])
    {
        $output     = [];
        $visitor    = $this->getVisitorLocation();
        $languages  = $this->getInternationalizationForResource($this->modx->resource);
        $parameters = $this->modx->request->getParameters($this->config['url_params']);

        foreach ($languages as $language) {
            if ($language['translation']) {
                $url    = $this->modx->makeUrl($language['translation']['translate_id'], $language['key'], null, 'full');
                $locale = str_replace('_', '-', $language['locale']);

                if ($url) {
                    $output[] = '<link rel="alternate" hreflang="' . $locale . '" href="' . $url . '" />';
                }
            }

            if (isset($visitor['languages'])) {
                if (!isset($visitor['context']) || (isset($visitor['context']) && $visitor['context'] !== $this->modx->context->get('key'))) {
                    foreach ((array) $visitor['languages'] as $visitorLanguage) {
                        if (in_array($visitorLanguage, $language['locales'], true)) {
                            if (!$language['translation']) {
                                $url = $this->modx->makeUrl($language['fallback'], $language['key'], array_merge($parameters, [
                                    'language' => $language['key']
                                ]), 'full');
                            } else {
                                $url = $this->modx->makeUrl($language['translation']['translate_id'], $language['key'], array_merge($parameters, [
                                    'language' => $language['key']
                                ]), 'full');
                            }

                            $this->modx->toPlaceholders([
                                'key'           => $language['key'],
                                'name'          => $language['name'],
                                'locale'        => $language['locale'],
                                'suggested_url' => $url,
                                'current_url'   => $this->modx->makeUrl($this->modx->resource->get('id'), $this->modx->context->get('key'), array_merge($parameters, [
                                    'language'      => $this->modx->context->get('key')
                                ]), 'full')
                            ], 'internationalization.language.suggestion');
                        }
                    }
                }
            }
        }

        $context        = $this->modx->context;
        $defaultContext = $this->modx->getOption('default_context', null, 'web');

        if ($context->get('key') !== $defaultContext) {
            $context = $this->modx->getContext($defaultContext);
        }

        if ($context) {
            $output[] = '<link rel="alternate" hreflang="x-default" href="' . $this->modx->makeUrl($context->getOption('site_start'), $context->get('key'), null, 'full') . '" />';
        }

        $this->modx->setPlaceholder('internationalization.language.alternate', implode(PHP_EOL, $output));
    }

    /**
     * @access public.
     * @param Array $properties.
     */
    public function onDocFormRender(array $properties = [])
    {
        if ($this->modx->hasPermission('internationalization')) {
            $this->modx->regClientCSS($this->config['css_url'] . 'mgr/internationalization.css');

            $this->modx->regClientStartupScript($this->config['js_url'] . 'mgr/internationalization.js');

            $this->modx->regClientStartupScript($this->config['js_url'] . 'mgr/extras/extras.js');

            $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
                Ext.onReady(function() {
                    Internationalization.config = ' . $this->modx->toJSON(array_merge($this->config, [
                        'branding_url'          => $this->getBrandingUrl(),
                        'branding_url_help'     => $this->getHelpUrl()
                    ])) . ';
                                        
                    Internationalization.setMenu(' . $this->modx->toJSON([
                        'resource' => $properties['id'],
                        'contexts' => $this->getInternationalizationForResource($properties['resource'])
                    ]) . ');
                });
            </script>');

            if (is_array($this->config['lexicons'])) {
                foreach ($this->config['lexicons'] as $lexicon) {
                    $this->modx->controller->addLexiconTopic($lexicon);
                }
            } else {
                $this->modx->controller->addLexiconTopic($this->config['lexicons']);
            }
        }
    }

    /**
     * @access public.
     * @param Array $properties.
     */
    public function onEmptyTrash(array $properties = [])
    {
        if (isset($properties['resources'])) {
            $this->removeInternationalizations(array_keys($properties['resources']));
        }
    }

    /**
     * @access public.
     * @param Array $properties.
     */
    public function onResourceDuplicate(array $properties = [])
    {
        if (isset($properties['oldResource'], $properties['newResource'])) {
            $this->duplicateInternationalizations($properties['oldResource']->get('id'), $properties['newResource']->get('id'));
        }
    }
}
