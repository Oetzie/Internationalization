<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class InternationalizationTopicsGetListProcessor extends modObjectProcessor
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

        $namespace = $this->modx->getObject('modNamespace', [
            'name' => $this->getProperty('namespace')
        ]);

        if ($namespace) {
            $path = $namespace->getCorePath();

            foreach (glob(rtrim($path, '/') . '/lexicon/*/*.inc.php') as $file) {
                $name = substr(substr($file, strrpos($file, '/') + 1), 0, -8);

                if (!isset($output[$name])) {
                    $output[$name] = [
                        'id'    => $name,
                        'name'  => ucfirst($name)
                    ];
                }
            }
        }

        ksort($output);

        return $this->outputArray(array_values($output));
    }
}

return 'InternationalizationTopicsGetListProcessor';
