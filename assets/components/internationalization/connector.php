<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once dirname(dirname(dirname(__DIR__))) . '/config.core.php';

require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$modx->getService('internationalization', 'Internationalization', $modx->getOption('internationalization.core_path', null, $modx->getOption('core_path') . 'components/internationalization/') . 'model/internationalization/');

if ($modx->internationalization instanceOf Internationalization) {
    $modx->request->handleRequest([
        'processors_path'   => $modx->internationalization->config['processors_path'],
        'location'          => ''
    ]);
}
