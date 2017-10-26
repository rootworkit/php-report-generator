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

class Pdf extends SpreadsheetAbstract implements WriterInterface
{

    /**
     * Save to file.
     *
     * @param string $name
     */
    public function save($name)
    {
        $writer = SpreadsheetWriterFactory::createWriter($this->getSpreadsheet(), 'Mpdf');
        $writer->save($name);
    }

    /**
     * Output to user (i.e. web browser).
     *
     * @param string $name
     */
    public function output($name)
    {
        header('Content-Type: application/pdf');
        header("Content-Disposition: attachment;filename=\"$name\"");
        header('Cache-Control: max-age=0');

        $writer = SpreadsheetWriterFactory::createWriter($this->getSpreadsheet(), 'Mpdf');
        $writer->save('php://output');
    }
}