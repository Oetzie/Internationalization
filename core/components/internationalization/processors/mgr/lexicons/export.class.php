<?php

/**
 * Internationalization
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InternationalizationLexiconsExportProcessor extends modObjectProcessor
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
        $exportFile = $this->getProperty('file');
        $exportPath = rtrim($this->modx->getOption('core_path', null, MODX_CORE_PATH), '/') . '/export/';

        if (!empty($exportFile)) {
            if (file_exists(rtrim($exportPath, '/') . '/' . $exportFile)) {
                header('Content-Disposition: attachment; filename="' . $exportFile);
                header('Content-Type: text/xlsx');

                return file_get_contents(rtrim($exportPath, '/') . '/' . $exportFile);
            }

            return $this->failure($this->modx->lexicon('internationalization.export_failed'));
        }

        $lexicons   = [];
        $languages  = [];

        $namespace = $this->modx->getObject('modNamespace', [
            'name' => $this->getProperty('namespace')
        ]);

        if ($namespace) {
            $path   = $namespace->getCorePath();
            $topic  = $this->getProperty('topic') . '.inc.php';

            foreach (glob(rtrim($path, '/') . '/lexicon/*/') as $directory) {
                $language = substr(trim($directory, '/'), strrpos(trim($directory, '/'), '/') + 1);

                if (file_exists(rtrim($directory, '/') . '/' . $topic)) {
                    if (include rtrim($directory, '/') . '/' . $topic) {
                        if (isset($_lang) && is_array($_lang)) {
                            foreach ($_lang as $key => $value) {
                                $lexicons[$key][$language] = str_replace('\\\'', '\'', $value);
                            }
                        }
                    }
                }

                $languages[] = $language;
            }

            try {
                if ($spreadsheet = new Spreadsheet()) {
                    $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

                    if ($sheet = $spreadsheet->getActiveSheet()) {
                        $sheet->setCellValue('A1', 'key');

                        foreach ($languages as $column => $language) {
                            $sheet->setCellValue($columns[$column + 1] . '1', $language);
                        }

                        $row = 2;

                        foreach ($lexicons as $key => $value) {
                            $sheet->setCellValue('A' . $row, $key);

                            foreach ($languages as $column => $language) {
                                $sheet->setCellValue($columns[$column + 1] . $row, $value[$language] ?: '');
                            }

                            $row++;
                        }

                        if ($writer = new Xlsx($spreadsheet)) {
                            $exportFile = 'internationalization_' . date('ymd_hi') . '_' . $this->getProperty('topic') . '.xlsx';

                            $writer->save(rtrim($exportPath, '/') . '/' . $exportFile);

                            return $this->success($this->modx->lexicon('internationalization.export_success'), [
                                'file' => $exportFile
                            ]);
                        }
                    }

                    return $this->failure($this->modx->lexicon('internationalization.export_failed'));
                }
            } catch (Exception $e) {
                return $this->failure($this->modx->lexicon('internationalization.export_failed_desc', [
                    'error' => $e->getMessage()
                ]));
            }
        }

        return $this->failure($this->modx->lexicon('internationalization.export_failed'));
    }
}

return 'InternationalizationLexiconsExportProcessor';
