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
 * Semite Action Class
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
 * Description of Action
 *
 * @author ahmet
 */
final class Action {
	protected $file;
	protected $class;
        protected $bootstrap;
        protected $method;
	protected $module;
        protected $args = array();

	public function __construct($route, $args = array()) {
		$path = '';
		
		$parts = explode('/', str_replace('../', '', (string)$route));
                
                if (isset($parts[0])){
                    $this->module = ucfirst($parts[0]);
                }
		
		foreach ($parts as $part) { 

                        $path = $part;

			if (is_dir(APPLICATION_PATH_MOD .DS.$this->module.DS. 'controller/' . $path)) {
				$path .= '/';
				
				array_shift($parts);
				
				continue;
			}

			if (is_file(APPLICATION_PATH_MOD .DS .$this->module.DS. 'controller/' . str_replace(array('../', '..\\', '..'), '', $path) . '.php')) {
				$this->file = APPLICATION_PATH_MOD .DS .$this->module.DS. 'controller/' . str_replace(array('../', '..\\', '..'), '', $path) . '.php';
				
				$this->class = ucfirst($this->module)."_".'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $path);
                                
                                $this->bootstrap = ucfirst($this->module)."_".'Bootstrap';
                                
                                                                
				array_shift($parts);
				
				break;
			}
		}
		
		if ($args) {
			echo $this->args = $args;
		}
			
		$method = (isset($parts[2]) ? $parts[2] : null);
                			
		if ($method) {
			$this->method = $method;
		} else {
			$this->method = 'index';
		}
	}
	
	public function getFile() {
		return $this->file;
	}
	
	public function getClass() {
		return $this->class;
	}
        
        public function getBootstrap() {
		return $this->bootstrap;
	}
	
	public function getMethod() {
		return $this->method;
	}
        
        public function getModule() {
		return $this->module;
	}
	
	public function getArgs() {
		return $this->args;
	}
}

?>
