<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class InternationalizationContextGetListProcessor extends modObjectGetListProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'modContext';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['internationalization:default'];

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortField = 'key';

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortDirection = 'ASC';

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'internationalization.context';

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
     * @param xPDOQuery $criteria.
     * @return xPDOQuery.
     */
    public function prepareQueryBeforeCount(xPDOQuery $criteria)
    {
        $contexts = [];

        foreach ($this->modx->internationalization->config['contexts'] as $context) {
            $contexts = array_merge($contexts, $context);
        }

        $criteria->where([
            'key:IN' => $contexts
        ]);

        return $criteria;
    }

    /**
     * @access public.
     * @param xPDOObject $object.
     * @return Array.
     */
    public function prepareRow(xPDOObject $object)
    {
        return $object->toArray();
    }
}

return 'InternationalizationContextGetListProcessor';
