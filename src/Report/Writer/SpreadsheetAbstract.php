<?php
/**
 * SpreadsheetAbstract
 *
 * @package     Rootwork\Report\Writer
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report\Writer;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

abstract class SpreadsheetAbstract extends WriterAbstract
{

    /**
     * @var Spreadsheet
     */
    protected $spreadsheet;

    /**
     * Current row number in report.
     *
     * @var int
     */
    protected $currentRow = 1;

    /**
     * Get the populated Spreadsheet instance.
     *
     * @return Spreadsheet
     */
    protected function getSpreadsheet()
    {
        if (!$this->spreadsheet) {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setTitle($this->report->getDefinition()->getTitle())
                ->setSubject($this->report->getDefinition()->getTitle());

            $spreadsheet->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
            $this->spreadsheet = $spreadsheet;

            $this->addRow($this->report->getColumnDisplayNames());

            foreach ($this->report->getAllRows() as $rowData) {
                $this->addRow($rowData);
            }

            if ($this->report->getDefinition()->hasTotal()) {
                $range = "A{$this->currentRow}:Z{$this->currentRow}";
                $spreadsheet->getActiveSheet()->getStyle($range)->getFont()->setBold(true);
                $this->addRow($this->report->getTotals());
            }
        }

        return $this->spreadsheet;
    }

    /**
     * Add a row of values to the current worksheet.
     *
     * @param array $row
     *
     * @return $this
     */
    protected function addRow(array $row)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $col   = 'A';

        foreach ($row as $value) {
            $sheet->setCellValue($col . $this->currentRow, $value);
            $col++;
        }

        $this->currentRow++;

        return $this;
    }
}