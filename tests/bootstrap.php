<?php
/**
 * PHPUnit bootstrapper
 *
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     All Rights Reserved
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

defined('APP_ROOT') || define('APP_ROOT', realpath(dirname(__DIR__)));

require APP_ROOT . '/vendor/autoload.php';
require 'TestCase.php';