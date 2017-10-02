<?php
/**
 * Column
 *
 * @package     Rootwork\Report
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report;

/**
 * Class Column
 *
 * @package Rootwork\Report
 */
class Column
{

    /**
     * Type constants
     */
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_DATE    = 'date';
    const TYPE_FLOAT   = 'float';
    const TYPE_INTEGER = 'integer';
    const TYPE_STRING  = 'string';

    /**
     * Format constants
     */
    const FORMAT_CURRENCY = 'currency';
    const FORMAT_EMAIL    = 'email';
    const FORMAT_NUMBER   = 'number';
    const FORMAT_URL      = 'url';

    /**
     * The column name.
     *
     * @var string
     */
    protected $name;

    /**
     * The column display name.
     *
     * @var string
     */
    protected $display;

    /**
     * The column type.
     *
     * @var string
     */
    protected $type;

    /**
     * @var string|\Closure
     */
    protected $format = null;

    /**
     * Column constructor.
     *
     * @param string          $name
     * @param string          $display
     * @param string          $type
     * @param string|\Closure $format
     */
    public function __construct($name, $display, $type, $format = null)
    {
        $this->setName($name)
            ->setDisplay($display)
            ->setType($type)
            ->setFormat($format);
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
            self::TYPE_BOOLEAN,
            self::TYPE_DATE,
            self::TYPE_FLOAT,
            self::TYPE_INTEGER,
            self::TYPE_STRING,
        ];

        if (!in_array($type, $types)) {
            throw new \InvalidArgumentException("Invalid column type: '$type'");
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
     * Set the column format (can be a callback).
     *
     * @param string $format
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $formats = [
            self::FORMAT_CURRENCY,
            self::FORMAT_EMAIL,
            self::FORMAT_NUMBER,
            self::FORMAT_URL,
        ];

        if (null !== $format && !in_array($format, $formats)) {
            throw new \InvalidArgumentException("Invalid column format: '$format'");
        }

        $this->format = $format;

        return $this;
    }

    /**
     * @return \Closure|string
     */
    public function getFormat()
    {
        return $this->format;
    }
}