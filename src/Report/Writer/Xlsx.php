<?php
/**
 * XLSX report writer.
 *
 * @package     Rootwork\Report\Writer
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report\Writer;

use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetWriterFactory;
use Psr\Http\Message\ResponseInterface;

class Xlsx extends SpreadsheetAbstract implements WriterInterface
{

    /**
     * Save to file.
     *
     * @param string $name
     */
    public function save($name)
    {
        $writer = SpreadsheetWriterFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save($name);
    }

    /**
     * Output to user (i.e. web browser).
     *
     * @param string                 $name
     * @param ResponseInterface|null $response
     *
     * @return ResponseInterface|null
     */
    public function output($name, ResponseInterface $response = null)
    {
        $writer  = SpreadsheetWriterFactory::createWriter($this->spreadsheet, 'Xlsx');
        $headers = [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=\"$name\"",
            'Cache-Control'       => 'max-age=0',
        ];

        if ($response) {
            foreach ($headers as $name => $value) {
                $response = $response->withHeader($name, $value);
            }

            ob_start();
            $writer->save('php://output');
            $output = ob_get_clean();
            $response->getBody()->write($output);

            return $response;
        }

        foreach ($headers as $name => $value) {
            header("$name: $value");
        }

        $writer->save('php://output');
        return null;
    }
}