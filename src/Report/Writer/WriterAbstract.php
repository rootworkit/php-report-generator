<?php
/**
 * WriterAbstract
 *
 * @package     Rootwork\Report\Writer
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report\Writer;

use Rootwork\Report\ReportInterface;

abstract class WriterAbstract implements WriterInterface
{

    /**
     * @var ReportInterface
     */
    protected $report;

    /**
     * WriterAbstract constructor.
     *
     * @param ReportInterface $report
     */
    public function __construct(ReportInterface $report)
    {
        $this->report = $report;
    }

    /**
     * Save to file or stream.
     *
     * @param string $name
     */
    abstract public function save($name);
}
