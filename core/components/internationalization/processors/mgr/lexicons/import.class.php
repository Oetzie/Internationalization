<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class InternationalizationLexiconsImportProcessor extends modObjectProcessor
{
    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['internationalization:default'];

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'internationalization.lexicons';

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
     * @return Mixed.
     */
    public function process()
    {
        $namespace = $this->modx->getObject('modNamespace', [
            'name' => $this->getProperty('namespace')
        ]);

        if ($namespace) {
            $path   = $namespace->getCorePath();
            $topic  = $this->getProperty('topic') . '.inc.php';

            try {
                if ($reader = new Xlsx()) {
                    if ($spreadsheet = $reader->load($this->getProperty('file')['tmp_name'])) {
                        $spreadsheet = $spreadsheet->getActiveSheet();

                        $rows = [];

                        foreach ($spreadsheet->getRowIterator() as $row) {
                            $cellIterator = $row->getCellIterator();
                            $cellIterator->setIterateOnlyExistingCells(false);

                            $cells = [];

                            foreach ($cellIterator as $cell) {
                                $cells[] = $cell->getValue();
                            }

                            $rows[] = $cells;
                        }

                        $lexicons   = [];
                        $languages  = [];

                        foreach (array_shift($rows) as $language) {
                            if (!empty($language) && $language !== 'key') {
                                $languages[] = strtolower($language);
                            }
                        }

                        foreach ($rows as $row) {
                            $key = array_shift($row);

                            foreach ($row as $language => $value) {
                                if (isset($languages[$language])) {
                                    $lexicons[$key][$languages[$language]] = $value;
                                }
                            }
                        }

                        if (count($lexicons) >= 1) {
                            foreach ($languages as $language) {
                                $output = [];

                                $languagePath = rtrim($path, '/') . '/lexicon/' . $language . '/';
                                $languageFile = $languagePath . $topic;

                                if (!mkdir($languagePath) && !is_dir($languagePath)) {
                                    continue;
                                }

                                $file = fopen($languageFile, 'wb');

                                if ($file) {
                                    foreach ($lexicons as $key => $values) {
                                        if (!empty($key)) {
                                            $output[] = '$_lang[\'' . $key . '\'] = \'' . str_replace('\'', '\\\'', $values[$language] ?: '') . '\';';
                                        }
                                    }

                                    fwrite($file, '<?php' . PHP_EOL . implode(PHP_EOL, $output));
                                    fclose($file);
                                }
                            }

                            return $this->success($this->modx->lexicon('internationalization.import_success'));
                        }

                        return $this->failure($this->modx->lexicon('internationalization.import_failed_desc', [
                            'error' => $this->modx->lexicon('internationalization.import_no_lexicons')
                        ]));
                    }
                }
            } catch (Exception $e) {
                return $this->failure($this->modx->lexicon('internationalization.import_failed_desc', [
                    'error' => $e->getMessage()
                ]));
            }
        }

        return $this->failure($this->modx->lexicon('internationalization.import_failed_desc'));
    }
}

return 'InternationalizationLexiconsImportProcessor';
