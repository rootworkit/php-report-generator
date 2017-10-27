<?php
/**
 * WriterInterface
 *
 * @package     Rootwork\Report\Writer
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report\Writer;

interface WriterInterface
{

    /**
     * Save to file or stream.
     *
     * @param string $name
     */
    public function save($name);
}