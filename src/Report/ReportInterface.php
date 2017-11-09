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
     * @return Column[]
     */
    public function getColumns();

    /**
     * Get the resulting column display names after running report.
     *
     * @return array
     */
    public function getColumnDisplayNames();

    /**
     * Get current page of report rows.
     *
     * @return array
     */
    public function getRows();

    /**
     * Get all rows (bypass paging).
     *
     * @return array
     */
    public function getAllRows();

    /**
     * @return Definition
     */
    public function getDefinition();

    /**
     * @return array
     */
    public function getTotals();
}