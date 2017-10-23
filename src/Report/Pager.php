<?php
/**
 * Pager: Holds paging metadata and, optionally, pages rows.
 *
 * @package     Rootwork\Report
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report;

class Pager implements \JsonSerializable
{

    /**
     * The current page number.
     *
     * @var int
     */
    protected $page = 1;

    /**
     * Total number of pages.
     *
     * @var int
     */
    protected $pageCount = 1;

    /**
     * Total number of rows.
     *
     * @var int
     */
    protected $rowCount = 0;

    /**
     * Number of items per page.
     *
     * @var int|null
     */
    protected $limit = null;

    /**
     * By default, the pager does not actually do the work of paging results.
     * If data is being loaded from a database, using the LIMIT and OFFSET clauses
     * to page your data at query time is the most efficient approach.
     *
     * However, if your data set is not already paged, you can set 'active' to TRUE
     * and the results will be paged when getRows() is called.
     *
     * @var bool
     */
    protected $active = false;

    /**
     * Pager constructor.
     *
     * @param int      $page
     * @param int      $pageCount
     * @param int      $rowCount
     * @param int|null $limit
     * @param bool     $active
     */
    public function __construct($page = 1, $pageCount = 1, $rowCount = 0, $limit = null, $active = false)
    {
        $this->setPage($page)
            ->setPageCount($pageCount)
            ->setRowCount($rowCount)
            ->setLimit($limit)
            ->setActive($active);
    }

    /**
     * Set the current page number.
     *
     * @param int $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = intval($page);
        return $this;
    }

    /**
     * Get the current page number.
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set the total number of pages.
     *
     * @param int $pageCount
     *
     * @return $this
     */
    public function setPageCount($pageCount)
    {
        $this->pageCount = intval($pageCount);
        return $this;
    }

    /**
     * Get the total number of pages.
     *
     * @return int
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * Set the total number of rows.
     *
     * @param int $rowCount
     *
     * @return $this
     */
    public function setRowCount($rowCount)
    {
        $this->rowCount = intval($rowCount);
        return $this;
    }

    /**
     * Get the total number of rows.
     *
     * @return int
     */
    public function getRowCount()
    {
        return $this->rowCount;
    }

    /**
     * Set the number of items per page.
     *
     * @param int|null $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = is_numeric($limit) ? intval($limit) : null;
        return $this;
    }

    /**
     * Get the number of items per page.
     *
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Enable/disable active paging.
     *
     * @param bool $active
     *
     * @return bool
     */
    public function setActive($active)
    {
        $this->active = boolval($active);
        return $this->active;
    }

    /**
     * Determine if active paging is enabled.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Get a page from a row set.
     *
     * @param array $rows
     *
     * @return array
     */
    public function getPagedRows(array $rows)
    {
        $this->setRowCount(count($rows));
        $pagedRows = $rows;

        if ($limit = $this->getLimit()) {
            $this->setPageCount($this->getRowCount() / $limit);
            $page      = $this->getPage();
            $start     = ($page - 1) * $limit;
            $pagedRows = array_slice($rows, $start, $limit);
        }

        return $pagedRows;
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
            'page'      => $this->getPage(),
            'pageCount' => $this->getPageCount(),
            'rowCount'  => $this->getRowCount(),
            'limit'     => $this->getLimit(),
            'active'    => $this->isActive(),
        ];
    }
}
