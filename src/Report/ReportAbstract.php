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
     * @return array[]
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

            $totals[] = $value;
        }

        return $totals;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $columns = [];

        foreach ($this->definition->getColumns() as $column) {
            $columns[] = [
                'name'   => $column->getDisplay(),
                'type'   => $column->getType(),
                'format' => $column->getFormat(),
            ];
        }

        $data = [
            'title' => $this->definition->getTitle(),
            'columns' => $columns,
            'rows' => $this->getRows(),
        ];

        if ($this->getDefinition()->hasTotal()) {
            $data['totals'] = $this->getTotals();
        }

        return $data;
    }
}
