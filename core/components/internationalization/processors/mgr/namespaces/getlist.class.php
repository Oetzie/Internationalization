<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class InternationalizationNamespacesGetListProcessor extends modObjectProcessor
{
    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['internationalization:default'];

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'internationalization.topic';

    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('internationalization', 'Internationalization', $this->modx->getOption('internationalization.core_path', null, $this->modx->getOption('core_path') . 'components/internationalization/') . 'model/internationalization/');

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function process()
    {
        $output = [];

        foreach ($this->modx->getCollection('modNamespace') as $namespace) {
            $output[$namespace->get('name')] = [
                'name' => $namespace->get('name')
            ];
        }

        ksort($output);

        return $this->outputArray(array_values($output));
    }
}

return 'InternationalizationNamespacesGetListProcessor';
