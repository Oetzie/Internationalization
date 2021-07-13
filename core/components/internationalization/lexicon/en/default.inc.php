<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$_lang['internationalization']                                  = 'Internationalization';
$_lang['internationalization.desc']                             = 'Mange the internationalization settings here';

$_lang['area_internationalization']                             = 'Internationalization';

$_lang['setting_internationalization.branding_url']             = 'Branding';
$_lang['setting_internationalization.branding_url_desc']        = 'The URL of the branding button, if the URL is empty the branding button won\'t be shown.';
$_lang['setting_internationalization.branding_url_help']        = 'Branding (help)';
$_lang['setting_internationalization.branding_url_help_desc']   = 'The URL of the branding help button, if the URL is empty the branding help button won\'t be shown.';
$_lang['setting_internationalization.contexts']                 = 'Contexts';
$_lang['setting_internationalization.contexts_desc']            = 'The contexts that will be used for internationalization, this must be a valid JSON format. For example \'[["web", "web-en"]]\'.';
$_lang['setting_internationalization.params']                   = 'URL parameters';
$_lang['setting_internationalization.params_desc']              = 'De URL parameters die overgenomen worden om de URL te genereren voor een vertaling. Meerdere URL parameters scheiden met een komma.';
$_lang['setting_internationalization.ip_provider']              = 'IP API data provider';
$_lang['setting_internationalization.ip_provider_desc']         = 'The IP API data provider, this can be \'ipstack\' or \'ipapi\'. If empty, IP API will not be used.';
$_lang['setting_internationalization.ip_provider_token']        = 'IP API data provider key';
$_lang['setting_internationalization.ip_provider_token_desc']   = 'The IP API data provider key.';

$_lang['internationalization.resource']                         = 'Resource';
$_lang['internationalization.resources']                        = 'Resources';
$_lang['internationalization.resources_desc']                   = 'Here you can view all pages per context and link to existing pages within a different context.';
$_lang['internationalization.resource_create']                  = 'Create resource';
$_lang['internationalization.resource_create_confirm']          = 'Are you sure you want to create a new resource in "<strong>[[+context]]</strong>".';
$_lang['internationalization.resource_open']                    = 'Open resource [[+resource]]';
$_lang['internationalization.resource_link']                    = 'Link to existing resource';
$_lang['internationalization.resource_unlink']                  = 'Unlink from resource [[+resource]]';
$_lang['internationalization.resource_unlink_confirm']          = 'Are you sure you want to unlink resource "<strong>[[+resource]]</strong>"';

$_lang['internationalization.label_resource']                   = 'Resource';
$_lang['internationalization.label_resource_desc']              = 'Select a page to serve as a translation.';

$_lang['internationalization.export']                           = 'Export translations';
$_lang['internationalization.export_desc']                      = 'Below you can export the different translations of a certain namespace to an easy Excel overview.';
$_lang['internationalization.import']                           = 'Import translations';
$_lang['internationalization.import_desc']                      = 'Below you can import the different translations of a certain namespace from an Excel overview.';

$_lang['internationalization.label_namespace']                  = 'Namespace';
$_lang['internationalization.label_namespace_desc']             = 'The namespace of the lexicons.';
$_lang['internationalization.label_topic']                      = 'Topic';
$_lang['internationalization.label_topic_desc']                 = 'The topic of the lexicons.';
$_lang['internationalization.label_import_file']                = 'File';
$_lang['internationalization.label_import_file_desc']           = 'Please select a valid xlsx file with translations.';

$_lang['internationalization.select_a_resource']                = 'Select a resource';
$_lang['internationalization.create_succeed']                   = 'Creating the resource was successful.';
$_lang['internationalization.create_failed']                    = 'Failed to create the resource.';
$_lang['internationalization.link_succeed']                     = 'Linking the resource was successful.';
$_lang['internationalization.link_failed']                      = 'Failed to link the resource.';
$_lang['internationalization.unlink_succeed']                   = 'Unlinking the resource was successful.';
$_lang['internationalization.unlink_failed']                    = 'Failed to unlink the resource.';
$_lang['internationalization.translation_of']                   = 'Translation of [[+name]]';
$_lang['internationalization.select_a_namespace']               = 'Select a namespace';
$_lang['internationalization.select_a_topic']                   = 'Select a topic';
$_lang['internationalization.export_success']                   = 'The translations have been exported successfully.';
$_lang['internationalization.export_failed']                    = 'Failed to export the translations.';
$_lang['internationalization.export_failed_desc']               = 'Failed to export the translations, [[+error]].';
$_lang['internationalization.export_no_lexicons']               = 'there are no translations available';
$_lang['internationalization.import_success']                   = 'The translations have been imported successfully.';
$_lang['internationalization.import_failed']                    = 'Failed to import the translations.';
$_lang['internationalization.import_failed_desc']               = 'Failed to import the translations, [[+error]].';
$_lang['internationalization.import_no_lexicons']               = 'there are no translations available';

$_lang['internationalization.select_language']                  = 'This page is also available in your own language, <a href="[[+url]]" title="click here">click here</a> to see this page in your own language.';
