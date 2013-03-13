<?php

if (!defined('_ENGINE'))
    exit('No direct script access allowed');

/**
 * Semite
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		Semite
 * @author		Semite Project Dev Team
 * @copyright	Copyright (c) 2008 - 2011, Semite Project, Inc.
 * @license		http://semiteproject.com/user_guide/license.html
 * @link		http://semiteproject.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Semite Database Class
 *
 * 
 *
 * @package		Semite
 * @subpackage	
 * @category	
 * @author		Semite Project Dev Team
 * @link		http://semiteproject.com/license-default.html
 */

/**
 * Description of Database
 *
 * @author ahmet
 */
class Database {

    private $driver;

    public function __construct($driver, $hostname, $username, $password, $database) {
        if (file_exists(APPLICATION_PATH_SYS.DS.'libraries'.DS.'Engine'.DS.'Database'.DS . ucfirst($driver) . '.php')) {
            require_once(APPLICATION_PATH_SYS.DS.'libraries'.DS.'Engine'.DS.'Database'.DS . ucfirst($driver) . '.php');
        } else {
            exit('Error: Could not load database file ' . ucfirst($driver) . '!');
        }

        $this->driver = new $driver($hostname, $username, $password, $database);
    }

    public function query($sql) {
        return $this->driver->query($sql);
    }

    public function escape($value) {
        return $this->driver->escape($value);
    }

    public function countAffected() {
        return $this->driver->countAffected();
    }

    public function getLastId() {
        return $this->driver->getLastId();
    }

}

?>
