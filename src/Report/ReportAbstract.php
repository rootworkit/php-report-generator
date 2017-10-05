<?php
/**
 * Report
 *
 * @package     Rootwork\Report
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report;

/**
 * Basic report abstract class
 *
 * @package Rootwork\Report
 */
abstract class ReportAbstract
{

    /**
     * The report definition.
     *
     * @var Definition
     */
    protected $definition;

    /**
     * Rows of report data.
     *
     * @var array
     */
    protected $rows = [];

    /**
     * ReportAbstract constructor.
     *
     * @param Definition $definition
     * @param array $options
     */
    public function __construct(Definition $definition, array $options = [])
    {
        $this->definition = $definition;
    }

    /**
     * Run the report and return results.
     *
     * @return array[]
     */
    abstract public function run();

    /**
     * Get the report rows.
     *
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Get the column totals.
     *
     * @return array
     */
    public function getTotals()
    {
        $totals = [];

        foreach ($this->definition->getColumns() as $column) {
            $value = null;

            if ($column->isTotal()) {
                $value = array_sum(array_column($this->getRows(), $column->getName()));
            }

            $totals[$column->getName()] = $value;
        }

        return $totals;
    }
}
