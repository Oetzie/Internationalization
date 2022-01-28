<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class Internationalization
{
    const DATA_COOKIE_NAME = 'internationalization_data';

    /**
     * @access public.
     * @var modX.
     */
    public $modx;

    /**
     * @access public.
     * @var Array.
     */
    public $config = [];

    /**
     * @access public.
     * @param modX $modx.
     * @param Array $config.
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath   = $this->modx->getOption('internationalization.core_path', $config, $this->modx->getOption('core_path') . 'components/internationalization/');
        $assetsUrl  = $this->modx->getOption('internationalization.assets_url', $config, $this->modx->getOption('assets_url') . 'components/internationalization/');
        $assetsPath = $this->modx->getOption('internationalization.assets_path', $config, $this->modx->getOption('assets_path') . 'components/internationalization/');

        $this->config = array_merge([
            'namespace'             => 'internationalization',
            'lexicons'              => ['internationalization:default'],
            'base_path'             => $corePath,
            'core_path'             => $corePath,
            'model_path'            => $corePath . 'model/',
            'processors_path'       => $corePath . 'processors/',
            'elements_path'         => $corePath . 'elements/',
            'chunks_path'           => $corePath . 'elements/chunks/',
            'plugins_path'          => $corePath . 'elements/plugins/',
            'snippets_path'         => $corePath . 'elements/snippets/',
            'templates_path'        => $corePath . 'templates/',
            'assets_path'           => $assetsPath,
            'js_url'                => $assetsUrl . 'js/',
            'css_url'               => $assetsUrl . 'css/',
            'assets_url'            => $assetsUrl,
            'connector_url'         => $assetsUrl . 'connector.php',
            'version'               => '1.0.1',
            'branding_url'          => $this->modx->getOption('internationalization.branding_url', null, ''),
            'branding_help_url'     => $this->modx->getOption('internationalization.branding_url_help', null, ''),
            'use_pdotools'          => (bool) $this->modx->getOption('internationalization.use_pdotools', null, false),
            'contexts'              => json_decode($this->modx->getOption('internationalization.contexts', null, ''), true),
            'url_params'            => array_filter(explode(',', $this->modx->getOption('internationalization.params')))
        ], $config);

        $this->modx->addPackage('internationalization', $this->config['model_path']);

        if (is_array($this->config['lexicons'])) {
            foreach ($this->config['lexicons'] as $lexicon) {
                $this->modx->lexicon->load($lexicon);
            }
        } else {
            $this->modx->lexicon->load($this->config['lexicons']);
        }
    }

    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getHelpUrl()
    {
        if (!empty($this->config['branding_help_url'])) {
            return $this->config['branding_help_url'] . '?v=' . $this->config['version'];
        }

        return false;
    }

    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getBrandingUrl()
    {
        if (!empty($this->config['branding_url'])) {
            return $this->config['branding_url'];
        }

        return false;
    }

    /**
     * @access public.
     * @param String $key.
     * @param Array $options.
     * @param Mixed $default.
     * @return Mixed.
     */
    public function getOption($key, array $options = [], $default = null)
    {
        if (isset($options[$key])) {
            return $options[$key];
        }

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $this->modx->getOption($this->config['namespace'] . '.' . $key, $options, $default);
    }

    /**
     * @access public.
     * @param String $name.
     * @param Array $properties.
     * @return String.
     */
    public function getChunk($name, array $properties = [])
    {
        if ($this->config['use_pdotools'] && $pdoTools = $this->modx->getService('pdoTools')) {
            return $pdoTools->getChunk($name, $properties);
        }

        $type = 'CHUNK';

        if (strpos($name, '@') === 0) {
            $type = substr($name, 1, strpos($name, ' ') - 1);
            $name = ltrim(substr($name, strpos($name, ' ') + 1, strlen($name)));
        }

        switch (strtoupper($type)) {
            case 'FILE':
                $name = $this->config['core_path'] . $name;

                if (file_exists($name)) {
                    $chunk = $this->modx->newObject('modChunk', [
                        'name' => $this->config['namespace'] . uniqid()
                    ]);

                    if ($chunk) {
                        $chunk->setCacheable(false);

                        return $chunk->process($properties, file_get_contents($name));
                    }
                }

                break;
            case 'INLINE':
                $chunk = $this->modx->newObject('modChunk', [
                    'name' => $this->config['namespace'] . uniqid()
                ]);

                if ($chunk) {
                    $chunk->setCacheable(false);

                    return $chunk->process($properties, $name);
                }

                break;
        }

        return $this->modx->getChunk($name, $properties);
    }

    /**
     * @access public.
     * @return null|array.
     */
    public function getVisitorLocation()
    {
        $provider = $this->modx->getOption('internationalization.ip_provider');

        if (in_array($provider, ['ipstack', 'ipapi'], true)) {
            $ip = $_SERVER['REMOTE_ADDR'];

            if (isset($_COOKIE[self::DATA_COOKIE_NAME])) {
                $data = unserialize($_COOKIE[self::DATA_COOKIE_NAME]);

                if (isset($_REQUEST['language'])) {
                    $data['context'] = $_REQUEST['language'];

                    setcookie(self::DATA_COOKIE_NAME, serialize($data), strtotime('+30 days'));
                }

                if (!empty($data['ip']) && !empty($data['country']) && $data['ip'] === $ip) {
                    return $data;
                }
            }

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_HEADER          => false,
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_TIMEOUT         => 10
            ]);

            if ($provider === 'ipstack') {
                curl_setopt_array($curl, [
                    CURLOPT_URL     => 'http://api.ipstack.com/' . $ip . '?' . http_build_query([
                        'access_key'    => $this->modx->getOption('internationalization.ip_provider_token')
                    ])
                ]);
            } else if ($provider === 'ipapi') {
                curl_setopt_array($curl, [
                    CURLOPT_URL     => 'https://pro.ip-api.com/json/' . $ip . '?' . http_build_query([
                        'key'           => $this->modx->getOption('internationalization.ip_provider_token')
                    ])
                ]);
            }

            $response       = curl_exec($curl);
            $responseInfo   = curl_getinfo($curl);

            curl_close($curl);

            if (isset($responseInfo['http_code']) && (int) $responseInfo['http_code'] === 200) {
                $response = json_decode($response, true);

                if ($response) {
                    $data = [
                        'ip'        => $ip,
                        'country'   => null,
                        'languages' => [],
                        'lat'       => null,
                        'lng'       => null
                    ];

                    if (!empty($response['country_code'])) {
                        $data['country']        = strtolower($response['country_code']);
                        $data['languages'][]    = strtolower($response['country_code']);
                    } else if (!empty($response['countryCode'])) {
                        $data['country']        = strtolower($response['countryCode']);
                        $data['languages'][]    = strtolower($response['countryCode']);
                    }

                    if (isset($response['location']['languages'])) {
                        foreach ((array) $response['location']['languages'] as $language) {
                            $data['languages'][] = strtolower($language['code']);
                        }
                    }

                    if (!empty($response['latitude'])) {
                        $data['lat'] = $response['latitude'];
                    } else if (!empty($response['lat'])) {
                        $data['lat'] = $response['lat'];
                    }

                    if (!empty($response['longitude'])) {
                        $data['lng'] = $response['longitude'];
                    } else if (!empty($response['lon'])) {
                        $data['lng'] = $response['lon'];
                    }

                    if (!empty($data['country'])) {
                        setcookie(self::DATA_COOKIE_NAME, serialize($data), strtotime('+30 days'));

                        return $data;
                    }
                }
            }

            $this->modx->log(modX::LOG_LEVEL_ERROR, '[Internationalization getVisitorLocation] Can\'t get user data for IP \'' . $ip . '\' from \'' . $provider . '\'.');
        }

        return null;
    }

    /**
     * @access public.
     * @param String $key.
     * @return Array.
     */
    public function getContextsFor($key)
    {
        $configs    = array_filter($this->config['contexts'], function ($contexts) use ($key) {
            return in_array($key, $contexts, true);
        });

        $contexts   = array_shift($configs);

        $criteria = $this->modx->newQuery('modContext');

        $criteria->select($this->modx->getSelectColumns('modContext', 'modContext'));
        $criteria->select($this->modx->getSelectColumns('modContextSetting', 'modContextSettingLocale', 'locale_', ['value']));
        $criteria->select($this->modx->getSelectColumns('modContextSetting', 'modContextSettingLocaleAlias', 'locale_alias_', ['value']));
        $criteria->select($this->modx->getSelectColumns('modContextSetting', 'modContextSettingIcon', 'icon_', ['value']));
        $criteria->select($this->modx->getSelectColumns('modContextSetting', 'modContextSettingFallback', 'fallback_', ['value']));
        $criteria->select($this->modx->getSelectColumns('modContextSetting', 'modContextSettingStatus', 'status_', ['value']));

        $criteria->leftJoin('modContextSetting', 'modContextSettingLocale', [
            '`modContextSettingLocale`.`key` = "locale"',
            '`modContextSettingLocale`.`context_key` = `modContext`.`key`'
        ]);

        $criteria->leftJoin('modContextSetting', 'modContextSettingLocaleAlias', [
            '`modContextSettingLocaleAlias`.`key` = "locale_alias"',
            '`modContextSettingLocaleAlias`.`context_key` = `modContext`.`key`'
        ]);

        $criteria->leftJoin('modContextSetting', 'modContextSettingIcon', [
            '`modContextSettingIcon`.`key` = "mgr_tree_icon_context"',
            '`modContextSettingIcon`.`context_key` = `modContext`.`key`'
        ]);

        $criteria->leftJoin('modContextSetting', 'modContextSettingFallback', [
            '`modContextSettingFallback`.`key` = "site_start"',
            '`modContextSettingFallback`.`context_key` = `modContext`.`key`'
        ]);

        $criteria->leftJoin('modContextSetting', 'modContextSettingStatus', [
            '`modContextSettingStatus`.`key` = "site_status"',
            '`modContextSettingStatus`.`context_key` = `modContext`.`key`'
        ]);

        $criteria->where([
            'modContext.key:!='     => $key,
            'AND:modContext.key:!=' => 'mgr'
        ]);

        if ($contexts) {
            $criteria->where([
                'modContext.key:IN' => $contexts
            ]);
        }

        $criteria->sortby('modContext.rank', 'ASC');

        return $this->modx->getCollection('modContext', $criteria);
    }

    /**
     * @access public.
     * @param String $key.
     * @return Array.
     */
    public function getInternationalizationForContext($key)
    {
        $output = [];

        foreach ($this->getContextsFor($key) as $context) {
            $output[] = [
                'key'           => $context->get('key'),
                'name'          => $context->get('locale_alias_value') ?: $context->get('name'),
                'description'   => $context->get('description'),
                'locale'        => $context->get('locale_value'),
                'locales'       => array_unique(explode('_', strtolower($context->get('locale_value')))),
                'icon'          => $context->get('icon_value'),
                'status'        => $context->get('status_value'),
                'fallback'      => $context->get('fallback_value')
            ];
        }

        return $output;
    }

    /**
     * @access public.
     * @param Object $resource.
     * @param Boolean $all.
     * @return Array.
     */
    public function getInternationalizationForResource($resource, $all = true)
    {
        $output = [];

        foreach ($this->getContextsFor($resource->get('context_key')) as $context) {
            $translation = $this->getResourceForContext($resource->get('id'), $context->get('key'));

            if ($translation) {
                $translation = $translation->toArray();
            }

            if ($all || $translation) {
                $output[$context->get('key')] = [
                    'key'           => $context->get('key'),
                    'name'          => $context->get('locale_alias_value') ?: $context->get('name'),
                    'description'   => $context->get('description'),
                    'locale'        => $context->get('locale_value'),
                    'locales'       => array_unique(explode('_', strtolower($context->get('locale_value')))),
                    'icon'          => $context->get('icon_value'),
                    'fallback'      => $context->get('fallback_value'),
                    'status'        => $context->get('status_value'),
                    'translation'   => $translation
                ];
            }
        }

        return $output;
    }

    /**
     * @access public.
     * @param Integer $id.
     * @param String $context.
     * @return Null|Object.
     */
    public function getResourceForContext($id, $context)
    {
        $criteria = $this->modx->newQuery('InternationalizationResource');

        $criteria->select($this->modx->getSelectColumns('InternationalizationResource', 'InternationalizationResource'));
        $criteria->select($this->modx->getSelectColumns('modResource', 'modResource', 'translate_', ['id', 'pagetitle', 'context_key']));

        $criteria->innerJoin('modResource', 'modResource', [
            '`modResource`.`id` = `InternationalizationResource`.`translate_id`'
        ]);

        $criteria->where([
            'InternationalizationResource.original_id'  => $id,
            'modResource.context_key'                   => $context
        ]);

        return $this->modx->getObject('InternationalizationResource', $criteria);
    }

    /**
     * @access public
     * @param Integer $originalId.
     * @param Integer $translateId.
     * @return Object|Null.
     */
    public function linkInternationalization($originalId, $translateId)
    {
        $objects = [[
            'original_id'   => $originalId,
            'translate_id'  => $translateId
        ], [
            'original_id'   => $translateId,
            'translate_id'  => $originalId
        ]];

        foreach ($objects as $criteria) {
            $object = $this->modx->getObject('InternationalizationResource', $criteria);

            if (!$object) {
                $object = $this->modx->newObject('InternationalizationResource', $criteria);

                if ($object) {
                    $object->save();
                }
            }
        }

        return $this->modx->getObject('modResource', [
            'id' => $originalId
        ]);
    }

    /**
     * @access public
     * @param Integer $originalId.
     * @param Integer $translateId.
     * @return Object|Null.
     */
    public function unlinkInternationalization($originalId, $translateId)
    {
        $objects = [[
            'original_id'   => $originalId,
            'translate_id'  => $translateId
        ], [
            'original_id'   => $translateId,
            'translate_id'  => $originalId
        ]];

        foreach ($objects as $criteria) {
            $object = $this->modx->getObject('InternationalizationResource', $criteria);

            if ($object) {
                $object->remove();
            }
        }

        return $this->modx->getObject('modResource', [
            'id' => $originalId
        ]);
    }

    /**
     * @access public.
     * @param Array $ids.
     */
    public function removeInternationalizations($ids)
    {
        $this->modx->removeCollection('InternationalizationResource', [
            'original_id:IN'        => $ids,
            'OR:translate_id:IN'    => $ids
        ]);
    }

    /**
     * @access public.
     * @param Integer $oldId.
     * @param Integer $newId.
     */
    public function duplicateInternationalizations($oldId, $newId)
    {
        $oldObjects = $this->modx->getCollection('InternationalizationResource', [
            'original_id' => $oldId
        ]);

        foreach ($oldObjects as $oldObject) {
            $newObject = $this->modx->newObject('InternationalizationResource');

            if ($newObject) {
                $newObject->fromArray([
                    'original_id'   => $newId,
                    'translate_id'  => $oldObject->get('translate_id')
                ]);

                $newObject->save();
            }
        }
    }
}
