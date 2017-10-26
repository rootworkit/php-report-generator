<?php
/**
 * WriterFactory
 *
 * @package     Rootwork\Report\Writer
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report\Writer;

use Rootwork\Report\ReportInterface;

class WriterFactory
{

    /**
     * Type constants
     */
    const TYPE_CSV  = 'CSV';
    const TYPE_PDF  = 'PDF';
    const TYPE_XLSX = 'XLSX';

    /**
     * Available writer classes.
     *
     * @var array
     */
    private static $writers = [
        self::TYPE_CSV  => Csv::class,
        self::TYPE_PDF  => Pdf::class,
        self::TYPE_XLSX => Xlsx::class,
    ];

    /**
     * Create a new report writer.
     *
     * @param ReportInterface $report
     * @param string          $type
     *
     * @return WriterInterface
     */
    public static function createWriter(ReportInterface $report, $type)
    {
        if (!isset(self::$writers[$type])) {
            throw new \InvalidArgumentException("Invalid writer type: '$type'");
        }

        $writerClass = self::$writers[$type];

        return new $writerClass($report);
    }
}
