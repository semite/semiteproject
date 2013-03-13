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
 * Semite Language Class
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
 * Description of Language
 *
 * @author ahmet
 */
class Language {

    private $default = 'english';
    private $directory;
    private $data = array();

    public function __construct($directory) {
        $this->directory = $directory;
    }

    public function get($key) {
        return (isset($this->data[$key]) ? $this->data[$key] : $key);
    }

    public function load($filename) {
        $file = APPLICATION_PATH_COR.DS.'languages'.DS . $this->directory . '/' . $filename . '.php';

        if (file_exists($file)) {
            $_ = array();

            require($file);

            $this->data = array_merge($this->data, $_);

            return $this->data;
        }

        $file = APPLICATION_PATH_COR.DS.'languages'.DS . $this->default . '/' . $filename . '.php';

        if (file_exists($file)) {
            $_ = array();

            require($file);

            $this->data = array_merge($this->data, $_);

            return $this->data;
        } else {
            trigger_error('Error: Could not load language ' . $filename . '!');
            //	exit();
        }
    }

}

?>
