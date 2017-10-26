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

use Psr\Http\Message\ResponseInterface;

interface WriterInterface
{

    /**
     * Save to file.
     *
     * @param string $name
     */
    public function save($name);

    /**
     * Output to user (i.e. web browser).
     *
     * If a PSR7 response is passed in, it will be used for
     * headers and content. Otherwise, PHP headers and
     * php://output are used.
     *
     * @param string                 $name
     * @param ResponseInterface|null $response
     *
     * @return
     */
    public function output($name, ResponseInterface $response = null);
}