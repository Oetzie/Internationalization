<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$_lang['internationalization']                                  = 'Internationalization';
$_lang['internationalization.desc']                             = 'Beheer hier de internationalization instellingen.';

$_lang['area_internationalization']                             = 'Internationalization';

$_lang['setting_internationalization.branding_url']             = 'Branding';
$_lang['setting_internationalization.branding_url_desc']        = 'De URL waar de branding knop heen verwijst, indien leeg wordt de branding knop niet getoond.';
$_lang['setting_internationalization.branding_url_help']        = 'Branding (help)';
$_lang['setting_internationalization.branding_url_help_desc']   = 'De URL waar de branding help knop heen verwijst, indien leeg wordt de branding help knop niet getoond.';
$_lang['setting_internationalization.contexts']                 = 'Contexten';
$_lang['setting_internationalization.contexts_desc']            = 'De contexten die gebruikt worden voor internationalization, dit moet een geldig JSON formaat zijn. Bijvoorbeeld \'[["web", "web-en"]]\'.';
$_lang['setting_internationalization.params']                   = 'URL parameters';
$_lang['setting_internationalization.params_desc']              = 'De URL parameters die overgenomen worden om de URL te genereren voor een vertaling. Meerdere URL parameters scheiden met een komma.';
$_lang['setting_internationalization.ip_provider']              = 'IP API data provider';
$_lang['setting_internationalization.ip_provider_desc']         = 'De IP API data provider, dit kan \'ipstack\' of \'ipapi\' zijn. Indien leeg zal IP API niet gebruikt worden.';
$_lang['setting_internationalization.ip_provider_token']        = 'IP API data provider key';
$_lang['setting_internationalization.ip_provider_token_desc']   = 'De IP API data provider key.';

$_lang['internationalization.resource']                         = 'Pagina';
$_lang['internationalization.resources']                        = 'Pagina\'s';
$_lang['internationalization.resources_desc']                   = 'Hier kun je alle pagina\'s per context bekijken en koppelen aan bestaande pagina\'s binnen een andere context.';
$_lang['internationalization.resource_create']                  = 'Nieuwe pagina';
$_lang['internationalization.resource_create_confirm']          = 'Weet je zeker dat je een nieuwe pagina wilt maken in "<strong>[[+context]]</strong>".';
$_lang['internationalization.resource_open']                    = 'Open pagina [[+resource]]';
$_lang['internationalization.resource_link']                    = 'Koppel aan bestaande pagina';
$_lang['internationalization.resource_unlink']                  = 'Ontkoppel pagina [[+resource]]';
$_lang['internationalization.resource_unlink_confirm']          = 'Weet je zeker zeker dat je de pagina "<strong>[[+resource]]</strong>" wilt ontkoppelen?';

$_lang['internationalization.label_resource']                   = 'Pagina';
$_lang['internationalization.label_resource_desc']              = 'Selecteer een pagina die als vertaling dient.';

$_lang['internationalization.export']                           = 'Exporteer vertalingen';
$_lang['internationalization.export_desc']                      = 'Hieronder kun je van een bepaalde namespace de verschillende vertalingen exporteren naar een makkelijk excell overzicht.';
$_lang['internationalization.import']                           = 'Importeer vertalingen';
$_lang['internationalization.import_desc']                      = 'Hieronder kun je van een bepaalde namespace de verschillende vertalingen exporteren naar een makkelijk excell overzicht.';

$_lang['internationalization.label_namespace']                  = 'Namespace';
$_lang['internationalization.label_namespace_desc']             = 'De namespace van de vertalingen.';
$_lang['internationalization.label_topic']                      = 'Topic';
$_lang['internationalization.label_topic_desc']                 = 'De topic van de vertalingen.';
$_lang['internationalization.label_import_file']                = 'Bestand';
$_lang['internationalization.label_import_file_desc']           = 'Selecteer een geldig xlsx bestand met vertalingen.';

$_lang['internationalization.select_a_resource']                = 'Selecteer een pagina';
$_lang['internationalization.create_succeed']                   = 'De pagina aanmaken is gelukt.';
$_lang['internationalization.create_failed']                    = 'De pagina aanmaken is mislukt.';
$_lang['internationalization.link_succeed']                     = 'De pagina koppelen is gelukt.';
$_lang['internationalization.link_failed']                      = 'De pagina koppelen is mislukt.';
$_lang['internationalization.unlink_succeed']                   = 'De pagina ontkoppelen is gelukt.';
$_lang['internationalization.unlink_failed']                    = 'De pagina ontkoppelen is mislukt.';
$_lang['internationalization.translation_of']                   = 'Vertaling van [[+name]]';
$_lang['internationalization.select_a_namespace']               = 'Selecteer een namespace';
$_lang['internationalization.select_a_topic']                   = 'Selecteer een topic';
$_lang['internationalization.export_success']                   = 'Het exporteren van de vertalingen is gelukt.';
$_lang['internationalization.export_failed']                    = 'Het exporteren van de vertalingen is mislukt.';
$_lang['internationalization.export_failed_desc']               = 'Het exporteren van de vertalingen is mislukt, [[+error]].';
$_lang['internationalization.export_no_lexicons']               = 'er zijn geen vertalingen beschikbaar';
$_lang['internationalization.import_success']                   = 'Het importeren van de vertalingen is gelukt.';
$_lang['internationalization.import_failed']                    = 'Het importeren van de vertalingen is mislukt.';
$_lang['internationalization.import_failed_desc']               = 'Het importeren van de vertalingen is mislukt, [[+error]].';
$_lang['internationalization.import_no_lexicons']               = 'er zijn geen vertalingen beschikbaar';

$_lang['internationalization.select_language']                  = 'This page is also available in your own language, <a href="[[+url]]" title="click here">click here</a> to see this page in your own language.';
