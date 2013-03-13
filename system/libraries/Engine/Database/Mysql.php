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
 * Semite Mysql Class
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
 * Description of Mysql
 *
 * @author ahmet
 */
final class MySQL {

    private $link;

    public function __construct($hostname, $username, $password, $database) {
        if (!$this->link = mysql_connect($hostname, $username, $password)) {
            trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
        }

        if (!mysql_select_db($database, $this->link)) {
            trigger_error('Error: Could not connect to database ' . $database);
        }

        mysql_query("SET NAMES 'utf8'", $this->link);
        mysql_query("SET CHARACTER SET utf8", $this->link);
        mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $this->link);
        mysql_query("SET SQL_MODE = ''", $this->link);
    }

    public function query($sql) {
        if ($this->link) {
            $resource = mysql_query($sql, $this->link);

            if ($resource) {
                if (is_resource($resource)) {
                    $i = 0;

                    $data = array();

                    while ($result = mysql_fetch_assoc($resource)) {
                        $data[$i] = $result;

                        $i++;
                    }

                    mysql_free_result($resource);

                    $query = new stdClass();
                    $query->row = isset($data[0]) ? $data[0] : array();
                    $query->rows = $data;
                    $query->num_rows = $i;

                    unset($data);

                    return $query;
                } else {
                    return true;
                }
            } else {
                trigger_error('Error: ' . mysql_error($this->link) . '<br />Error No: ' . mysql_errno($this->link) . '<br />' . $sql);
                exit();
            }
        }
    }

    public function escape($value) {
        if ($this->link) {
            return mysql_real_escape_string($value, $this->link);
        }
    }

    public function countAffected() {
        if ($this->link) {
            return mysql_affected_rows($this->link);
        }
    }

    public function getLastId() {
        if ($this->link) {
            return mysql_insert_id($this->link);
        }
    }

    public function __destruct() {
        if ($this->link) {
            mysql_close($this->link);
        }
    }

}

?>
