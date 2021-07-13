<?php
/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

if (in_array($modx->event->name, ['OnLoadWebDocument', 'OnDocFormRender', 'OnResourceDuplicate', 'OnEmptyTrash'], true)) {
    $instance = $modx->getService('internationalizationplugins', 'InternationalizationPlugins', $modx->getOption('internationalization.core_path', null, $modx->getOption('core_path') . 'components/internationalization/') . 'model/internationalization/');

    if ($instance instanceof InternationalizationPlugins) {
        $method = lcfirst($modx->event->name);

        if (method_exists($instance, $method)) {
            $instance->$method($scriptProperties);
        }
    }
}