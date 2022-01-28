<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once dirname(__DIR__) . '/internationalizationsnippets.class.php';

class InternationalizationGetLanguagesSnippet extends InternationalizationSnippets
{
    /**
     * @access public.
     * @var Array.
     */
    public $properties = [
        'id'                    => null,

        'tpl'                   => '@FILE elements/chunks/item.tpl',
        'tplWrapper'            => '@FILE elements/chunks/wrapper.tpl',
        'tplCurrent'            => '',

        'skipCurrent'           => false,
        'skipEmptyTranslation'  => false
    ];

    /**
     * @access public.
     * @param Array $properties.
     * @return String.
     */
    public function run(array $properties = [])
    {
        $this->setProperties($this->getFormattedProperties($properties));

        $output     = [];
        $resource   = $this->modx->resource;

        if ($this->getProperty('id')) {
            $resource = $this->modx->getObject('modResource', [
                'id' => $this->getProperty('id')
            ]);
        }

        if ($resource) {
            $current    = [
                'key'           => $this->modx->context->get('key'),
                'name'          => $this->modx->getOption('locale_alias', null, $this->modx->context->get('name')),
                'locale'        => $this->modx->getOption('locale'),
                'fallback'      => $this->modx->getOption('site_start'),
                'active'        => true,
                'status'        => true,
                'translation'   => [
                    'translate_id'  => $resource->get('id')
                ]
            ];
            $languages  = [];
            $parameters = $this->modx->request->getParameters($this->config['url_params']);

            foreach (array_merge([$current], $this->getInternationalizationForResource($resource)) as $language) {
                if (empty($language['status'])) {
                    continue;
                }

                $language['icon']   = strtolower(array_reverse(explode('_', $language['locale']))[0]);
                $language['url']    = $this->modx->makeUrl($language['fallback'], $language['key'], null, 'full');

                if ($language['translation']) {
                    $language['url']    = $this->modx->makeUrl($language['translation']['translate_id'], $language['key'], $parameters, 'full');
                } else {
                    if ($this->getProperty('skipEmptyTranslation')) {
                        continue;
                    }
                }

                if ($language['active']) {
                    $current = $language;
                }

                $languages[] = $language;
            }

            foreach ($languages as $language) {
                if (!isset($language['active']) || !$this->getProperty('skipCurrent')) {
                    $output[] = $this->getChunk($this->getProperty('tpl'), $language);
                }
            }

            if (count($output) >= 1) {
                $tplWrapper = $this->getProperty('tplWrapper');
                $tplCurrent = $this->getProperty('tplCurrent');

                if (!empty($tplCurrent)) {
                    $current = $this->getChunk($tplCurrent, $current);
                }

                if (!empty($tplWrapper)) {
                    return $this->getChunk($tplWrapper, [
                        'output'    => implode(PHP_EOL, $output),
                        'current'   => $current
                    ]);
                }

                implode(PHP_EOL, $output);
            }
        }

        return '';
    }
}
