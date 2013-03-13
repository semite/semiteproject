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
 * Semite Loader Class
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
 * Description of Loader
 *
 * @author ahmet
 */
final class Loader {

    protected $registry;

    public function __construct($registry) {
        $this->registry = $registry;
    }

    public function __get($key) {
        return $this->registry->get($key);
    }

    public function __set($key, $value) {
        $this->registry->set($key, $value);
    }

    public function library($library) {
        $file = APPLICATION_PATH_SYS.DS . 'libraries'.DS . $library . '.php';

        if (file_exists($file)) {
            include_once($file);
        } else {
            trigger_error('Error: Could not load library ' . $library . '!');
            exit();
        }
    }

    public function helper($helper) {
        $file = APPLICATION_PATH_SYS . DS.'Helper'.DS . $helper . '.php';

        if (file_exists($file)) {
            include_once($file);
        } else {
            trigger_error('Error: Could not load helper ' . $helper . '!');
            exit();
        }
    }

    public function model($model) {
        $file = APPLICATION_PATH_MOD.DS . 'model'.DS . $model . '.php';
        $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

        if (file_exists($file)) {
            include_once($file);

            $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
        } else {
            trigger_error('Error: Could not load model ' . $model . '!');
            exit();
        }
    }

    public function database($driver, $hostname, $username, $password, $database) {
        $file = APPLICATION_PATH_SYS.DS.'libraries'.DS.'Database'.DS . $driver . '.php';
        $class = 'Database' . preg_replace('/[^a-zA-Z0-9]/', '', $driver);

        if (file_exists($file)) {
            include_once($file);

            $this->registry->set(str_replace('/', '_', $driver), new $class($hostname, $username, $password, $database));
        } else {
            trigger_error('Error: Could not load database ' . $driver . '!');
            exit();
        }
    }

    public function config($config) {
        $this->config->load($config);
    }

    public function language($language) {
        return $this->language->load($language);
    }

}

?>
