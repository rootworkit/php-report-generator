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
class Column implements \JsonSerializable
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
     * Column format.
     *
     * @var string
     */
    protected $format = null;

    /**
     * Add a row with a total for this column?
     *
     * @var bool
     */
    protected $total = false;

    /**
     * Column constructor.
     *
     * @param string $name
     * @param string $display
     * @param string $type
     * @param string $format
     * @param bool   $total
     */
    public function __construct($name, $display, $type, $format = null, $total = false)
    {
        $this->setName($name)
            ->setDisplay($display)
            ->setType($type)
            ->setFormat($format)
            ->setTotal($total);
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

    /**
     * Set whether this column should be totaled.
     *
     * @param bool $total
     *
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = boolval($total);
        return $this;
    }

    /**
     * @return bool
     */
    public function isTotal()
    {
        return $this->total;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'name'    => $this->getName(),
            'display' => $this->getDisplay(),
            'type'    => $this->getType(),
            'format'  => $this->getFormat(),
            'total'   => $this->isTotal(),
        ];
    }
}
