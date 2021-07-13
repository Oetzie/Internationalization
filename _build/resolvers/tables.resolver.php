<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modx->addPackage('internationalization', $modx->getOption('internationalization.core_path', null, $modx->getOption('core_path') . 'components/internationalization/') . 'model/');

            $manager = $modx->getManager();

            $manager->createObjectContainer('InternationalizationResource');

            break;
    }
}

return true;
