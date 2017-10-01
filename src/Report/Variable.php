<?php
/**
 * Variable
 *
 * @package     Rootwork\Report
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report;

/**
 * Class Variable
 *
 * @package Rootwork\Report
 */
class Variable
{

    /**
     * Type constants
     */
    const TYPE_DATE = 'date';
    const TYPE_NUMBER = 'number';
    const TYPE_SELECT = 'select';
    const TYPE_SELECT_MULTIPLE = 'select-multiple';
    const TYPE_TEXT = 'text';

    /**
     * The variable name.
     *
     * @var string
     */
    protected $name;

    /**
     * The variable display name.
     *
     * @var string
     */
    protected $display;

    /**
     * The variable type.
     *
     * @var string
     */
    protected $type;

    /**
     * Options for variables with constrained values.
     *
     * @var array
     */
    protected $options = [];

    /**
     * The default value.
     *
     * @var mixed
     */
    protected $default;

    /**
     * Default to all selected for variables with multiple values.
     *
     * @var bool
     */
    protected $defaultAll = false;

    /**
     * Variable constructor.
     *
     * @param string     $name
     * @param string     $display
     * @param string     $type
     * @param mixed|null $default
     * @param array      $options
     * @param bool       $defaultAll
     */
    public function __construct($name, $display, $type, $default = null, array $options = [], $defaultAll = false)
    {
        $this->setName($name)
            ->setDisplay($display)
            ->setType($type)
            ->setDefault($default)
            ->setOptions($options)
            ->setDefaultAll($defaultAll);
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the display name.
     *
     * @param string $display
     *
     * @return $this
     */
    public function setDisplay($display)
    {
        $this->display = $display;
        return $this;
    }

    /**
     * Get the display name.
     *
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $types = [
            self::TYPE_DATE,
            self::TYPE_NUMBER,
            self::TYPE_SELECT,
            self::TYPE_SELECT_MULTIPLE,
            self::TYPE_TEXT,
        ];

        if (!in_array($type, $types)) {
            throw new \InvalidArgumentException("Invalid variable type: '$type'");
        }

        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the options for variables with constrained values.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the default value.
     *
     * @param mixed $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Should all values be selected for variables with multiple values?
     *
     * @param bool $defaultAll
     *
     * @return $this
     */
    public function setDefaultAll($defaultAll)
    {
        $this->defaultAll = boolval($defaultAll);
        return $this;
    }

    /**
     * @return bool
     */
    public function getDefaultAll()
    {
        return $this->defaultAll;
    }
}