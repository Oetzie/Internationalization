<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class InternationalizationResourceCreateProcessor extends modObjectProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $permission = 'internationalization';

    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'InternationalizationResource';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['internationalization'];

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'internationalization.resource';

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
     * @return String.
     */
    public function process()
    {
        if ($this->checkPermissions()) {
            $original = $this->modx->getObject('modResource', [
                'id' => $this->getProperty('id')
            ]);

            if ($original) {
                $duplicate = $original->duplicate([
                    'publishedMode' => 'unpublish',
                    'newName'       => $this->modx->lexicon('internationalization.translation_of', [
                        'name'          => $original->get('pagetitle')
                    ])
                ]);

                if ($duplicate) {
                    $duplicate->fromArray($this->getInternationalizationFields($original->get('parent')));
                    $duplicate->save();

                    $resource = $this->modx->internationalization->linkInternationalization($original->get('id'), $duplicate->get('id'));

                    if ($resource) {
                        return $this->success($this->modx->lexicon('internationalization.create_succeed'), [
                            'resource'  => $resource->get('id'),
                            'contexts'  => $this->modx->internationalization->getInternationalizationForResource($resource)
                        ]);
                    }
                }
            }

            return $this->failure($this->modx->lexicon('internationalization.create_failed'));
        }

        return $this->failure($this->modx->lexicon('access_denied'));
    }

    public function getInternationalizationFields($parent)
    {
        //if ($parent === 0) {
            return [
                'parent'        => 0,
                'context_key'   => $this->getProperty('context')
            ];
       // }

        // PARENT OPHALEN VAN VERTALING
    }
}

return 'InternationalizationResourceCreateProcessor';
