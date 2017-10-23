<?php
/**
 * ReportInterface
 *
 * @package     Rootwork\Report
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report;


interface ReportInterface
{

    /**
     * Run the report and return results.
     */
    public function run();

    /**
     * Set report parameters, including variables and pager settings.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters = []);

    /**
     * @return array
     */
    public function getRows();

    /**
     * @return Definition
     */
    public function getDefinition();

    /**
     * @return array
     */
    public function getTotals();
}