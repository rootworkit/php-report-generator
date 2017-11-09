<?php
/**
 * PDF report writer.
 *
 * @package     Rootwork\Report\Writer
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report\Writer;

use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetWriterFactory;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Writer\Pdf as PdfWriter;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Pdf extends SpreadsheetAbstract implements WriterInterface
{

    /**
     * Save to file.
     *
     * @param string $name
     */
    public function save($name)
    {
        /** @var IWriter|PdfWriter $writer */
        $writer = SpreadsheetWriterFactory::createWriter($this->getSpreadsheet(), 'Mpdf');
        $writer->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $writer->save($name);
    }
}