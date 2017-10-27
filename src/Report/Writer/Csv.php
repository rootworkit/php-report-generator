<?php
/**
 * CSV report writer.
 *
 * @package     Rootwork\Report\Writer
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report\Writer;

use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetWriterFactory;

class Csv extends SpreadsheetAbstract implements WriterInterface
{

    /**
     * Save to file.
     *
     * @param string $name
     */
    public function save($name)
    {
        $writer = SpreadsheetWriterFactory::createWriter($this->getSpreadsheet(), 'Csv');
        $writer->save($name);
    }
}