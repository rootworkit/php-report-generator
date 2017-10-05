<?php
/**
 * Definition
 *
 * @package     Rootwork\Report
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report;

/**
 * Report definition class.
 *
 * Parses a JSON report definition.
 *
 * @package Rootwork\Report
 */
class Definition
{

    /**
     * The report title.
     *
     * @var string
     */
    protected $title;

    /**
     * The report PHP class.
     *
     * @var string
     */
    protected $class;

    /**
     * Column definitions.
     *
     * @var Column[]
     */
    protected $columns = [];

    /**
     * @var Variable[]
     */
    protected $variables = [];

    /**
     * Definition constructor.
     *
     * @param string $jsonPath
     */
    public function __construct($jsonPath = null)
    {
        if ($jsonPath) {
            if (!is_readable($jsonPath)) {
                throw new \InvalidArgumentException("Unable to read JSON file: '$jsonPath'");
            }

            $json = json_decode(file_get_contents($jsonPath));

            if (!isset($json->title)) {
                throw new \UnexpectedValueException('Report definitions must have a title');
            }

            if (!isset($json->class)) {
                throw new \UnexpectedValueException('Report definitions must have a class name');
            }

            if (!isset($json->columns) || !is_array($json->columns)) {
                throw new \UnexpectedValueException('Report definitions must have an array of columns');
            }

            if (isset($json->variables) && !is_array($json->variables)) {
                throw new \UnexpectedValueException('Report variables must be in an array');
            }

            $this->setTitle($json->title);
            $this->setClass($json->class);
            $this->setColumnsArray($json->columns);

            if (isset($json->variables)) {
                $this->setVariablesArray($json->variables);
            }
        }
    }

    /**
     * Set the report title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the report class name.
     *
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * Get the report class name.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Add a column.
     *
     * @param Column $column
     *
     * @return $this
     */
    public function addColumn(Column $column)
    {
        $this->columns[] = $column;
        return $this;
    }

    /**
     * Set the column definitions.
     *
     * @param Column[] $columns
     *
     * @return $this
     */
    public function setColumns(array $columns = [])
    {
        $this->columns = [];

        foreach ($columns as $column) {
            $this->addColumn($column);
        }

        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Add a variable.
     *
     * @param Variable $variable
     *
     * @return $this
     */
    public function addVariable(Variable $variable)
    {
        $this->variables[$variable->getName()] = $variable;
        return $this;
    }

    /**
     * Set the variable definitions.
     *
     * @param Variable[] $variables
     *
     * @return $this
     */
    public function setVariables(array $variables = [])
    {
        $this->variables = [];

        foreach ($variables as $variable) {
            $this->addVariable($variable);
        }

        return $this;
    }

    /**
     * Get a report instance from the definition.
     *
     * @param array|null $options
     *
     * @return ReportInterface
     */
    public function getReport(array $options = null)
    {
        $class = $this->getClass();

        if ($options) {
            return new $class($this, $options);
        }

        return new $class($this);
    }

    /**
     * Get a variable by name.
     *
     * @param string $name
     *
     * @return null|Variable
     */
    public function getVariable($name)
    {
        foreach ($this->variables as $variable) {
            if ($variable->getName() == $name) {
                return $variable;
            }
        }

        return null;
    }

    /**
     * @return Variable[]
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Set variable values from an array.
     *
     * @param array $values
     *
     * @return $this
     */
    public function setVariableValues(array $values)
    {
        foreach ($this->variables as $variable) {
            if (array_key_exists($variable->getName(), $values)) {
                $variable->setValue($values[$variable->getName()]);
            }
        }

        return $this;
    }

    /**
     * Add columns from definition array.
     *
     * @param array $columns
     *
     * @return $this
     */
    protected function setColumnsArray(array $columns)
    {
        foreach ($columns as $column) {
            foreach (['name', 'display', 'type'] as $property) {
                if (!property_exists($column, $property)) {
                    throw new \UnexpectedValueException("Column definitions must contain a $property");
                }

                $format = isset($column->format) ? $column->format : null;
                $this->addColumn(new Column($column->name, $column->display, $column->type, $format));
            }
        }

        return $this;
    }

    /**
     * Add variables from definition array.
     *
     * @param array $variables
     *
     * @return $this
     */
    protected function setVariablesArray(array $variables)
    {
        foreach ($variables as $variable) {
            foreach (['name', 'display', 'type'] as $property) {
                if (!property_exists($variable, $property)) {
                    throw new \UnexpectedValueException("Column definitions must contain a $property");
                }

                $options = isset($variable->options) ? (array) $variable->options : [];
                $default = isset($variable->default) ? $variable->default : null;
                $format  = isset($variable->format) ? $variable->format : null;

                $this->addVariable(new Variable(
                    $variable->name,
                    $variable->display,
                    $variable->type,
                    $default,
                    $options,
                    $format
                ));
            }
        }

        return $this;
    }
}