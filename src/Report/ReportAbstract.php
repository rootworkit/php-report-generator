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
abstract class ReportAbstract implements \JsonSerializable
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
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->definition = new Definition();
        $this->define();
    }

    /**
     * Method for setting up the report definition.
     */
    abstract protected function define();

    /**
     * Run the report and return results.
     *
     * @return void
     */
    abstract public function run();

    /**
     * Get the report definition.
     *
     * @return Definition
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Set the variable values.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters = [])
    {
        $this->definition->setVariableValues($parameters);

        if ($pager = $this->getDefinition()->getPager()) {
            if (array_key_exists('page', $parameters)) {
                $pager->setPage($parameters['page']);
            }

            if (array_key_exists('limit', $parameters)) {
                $pager->setLimit($parameters['limit']);
            }
        }

        if (isset($parameters['order'])) {
            $this->getDefinition()->setOrder((array) $parameters['order']);
        }

        if (isset($parameters['timeZone'])) {
            if ($parameters['timeZone'][0] === 'x') {
                $parameters['timeZone'][0] = '+';
            }
            $timezone = new \DateTimeZone($parameters['timeZone']);
            $this->getDefinition()->setTimeZone($timezone);
        }

        return $this;
    }

    /**
     * Get the report rows.
     *
     * @return array
     */
    public function getRows()
    {
        if (empty($this->rows)) {
            $this->run();
        }

        if ($pager = $this->getDefinition()->getPager()) {
            return $pager->getPagedRows($this->rows);
        }

        return $this->rows;
    }

    /**
     * Get all report rows (bypass paging).
     *
     * @return array
     */
    public function getAllRows()
    {
        if (empty($this->rows)) {
            $this->run();
        }

        return $this->rows;
    }

    /**
     * Get the resulting columns after running report.
     *
     * @return Column[]
     */
    public function getColumns()
    {
        if (empty($this->rows)) {
            $this->run();
        }

        return $this->getDefinition()->getColumns();
    }

    /**
     * Get the resulting column display names after running report.
     *
     * @return array
     */
    public function getColumnDisplayNames()
    {
        if (empty($this->rows)) {
            $this->run();
        }

        return $this->getDefinition()->getColumnDisplayNames();
    }

    /**
     * Get the column totals.
     *
     * @return array
     */
    public function getTotals()
    {
        if (empty($this->rows)) {
            $this->run();
        }

        $totals = [];

        foreach ($this->getDefinition()->getColumns() as $column) {
            $value = null;

            if ($column->isTotal()) {
                $value = array_sum(array_column($this->getAllRows(), $column->getName()));
            }

            $totals[] = $value;
        }

        return $totals;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = [
            'title'   => $this->getDefinition()->getTitle(),
            'paging'  => $this->getDefinition()->getPager(),
            'columns' => $this->getColumns(),
            'rows'    => $this->getRows(),
            'order'   => $this->getDefinition()->getOrder(),
        ];

        if ($this->getDefinition()->hasTotal()) {
            $data['totals'] = $this->getTotals();
        }

        return $data;
    }
}
