<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class InternationalizationResourceUnlinkProcessor extends modObjectProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $permission = 'internationalization';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['internationalization'];

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
            $resource = $this->modx->internationalization->unlinkInternationalization($this->getProperty('original_id'), $this->getProperty('translate_id'));

            if ($resource) {
                return $this->success($this->modx->lexicon('internationalization.unlink_succeed'), [
                    'resource'  => $resource->get('id'),
                    'contexts'  => $this->modx->internationalization->getInternationalizationForResource($resource)
                ]);
            }

            return $this->failure($this->modx->lexicon('internationalization.unlink_failed'));
        }

        return $this->failure($this->modx->lexicon('access_denied'));
    }
}

return 'InternationalizationResourceUnlinkProcessor';
