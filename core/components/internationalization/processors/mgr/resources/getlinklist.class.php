<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class InternationalizationResourcesGetLinkListProcessor extends modObjectGetListProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'modResource';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['internationalization:default'];

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortField = 'pagetitle';

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortDirection = 'ASC';

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
     * @param xPDOQuery $criteria.
     * @return xPDOQuery.
     */
    public function prepareQueryBeforeCount(xPDOQuery $criteria)
    {
        $context = $this->getProperty('context');

        if (!empty($context)) {
            $criteria->where([
                'context_key' => $context
            ]);
        }

        $query = $this->getProperty('query');

        if (!empty($query)) {
            $criteria->where([
                'pagetitle:LIKE'    => '%' . $query . '%',
                'OR:longtitle:LIKE' => '%' . $query . '%'
            ]);
        }

        return $criteria;
    }

    /**
     * @access public.
     * @param xPDOObject $object.
     * @return Array.
     */
    public function prepareRow(xPDOObject $object)
    {
        return [
            'id'            => $object->get('id'),
            'context_key'   => $object->get('context_key'),
            'pagetitle'     => $object->get('pagetitle') . ($this->modx->hasPermission('tree_show_resource_ids') ? ' (' . $object->get('id') . ')' : ''),
            'contexts'      => $this->modx->internationalization->getInternationalizationForResource($object, false)
        ];
    }
}

return 'InternationalizationResourcesGetLinkListProcessor';
