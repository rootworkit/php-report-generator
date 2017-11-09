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

            $this->addRow($spreadsheet, $this->report->getColumnDisplayNames());

            foreach ($this->report->getAllRows() as $rowData) {
                $this->addRow($spreadsheet, $rowData);
            }

            $lastCol = $spreadsheet->getActiveSheet()->getHighestColumn();

            if ($this->report->getDefinition()->hasTotal()) {
                $this->addRow($spreadsheet, $this->report->getTotals());
                $range = 'A' . $this->currentRow . ':' . $lastCol . $this->currentRow;
                $spreadsheet->getActiveSheet()->getStyle($range)->getFont()->setBold(true);
            }

            $spreadsheet->getActiveSheet()->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);

            foreach (range('A', $lastCol) as $col) {
                $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }

            $this->spreadsheet = $spreadsheet;
        }

        return $this->spreadsheet;
    }

    /**
     * Add a row of values to the current worksheet.
     *
     * @param Spreadsheet $spreadsheet
     * @param array       $row
     *
     * @return $this
     */
    protected function addRow(Spreadsheet $spreadsheet, array $row)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $col   = 'A';

        foreach ($row as $value) {
            $sheet->setCellValue($col . $this->currentRow, $value);
            $col++;
        }

        $this->currentRow++;

        return $this;
    }
}