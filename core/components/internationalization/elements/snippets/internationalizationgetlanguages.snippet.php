<?php
/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$class = $modx->loadClass('InternationalizationGetLanguagesSnippet', $modx->getOption('internationalization.core_path', null, $modx->getOption('core_path') . 'components/internationalization/') . 'model/internationalization/snippets/', false, true);

if ($class) {
    $instance = new $class($modx);

    if ($instance instanceof InternationalizationGetLanguagesSnippet) {
        return $instance->run($scriptProperties);
    }
}

return '';