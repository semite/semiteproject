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
 * Semite Front Class
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
 * Description of Front
 *
 * @author ahmet
 */
final class Front {
	protected $registry;
	protected $pre_action = array();
	protected $error;
	
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function addPreAction($pre_action) {
		$this->pre_action[] = $pre_action;
	}
	
  	public function dispatch($action, $error) {
		$this->error = $error;
			
		foreach ($this->pre_action as $pre_action) {
			$result = $this->execute($pre_action);
					
			if ($result) {
				$action = $result;
				
				break;
			}
		}
			
		while ($action) {
			$action = $this->execute($action);
		}
  	}
    
	private function execute($action) {
		if (file_exists($action->getFile())) {
			require_once($action->getFile());
			
			$class = $action->getClass();

			$controller = new $class($this->registry);
                        
			
			if (is_callable(array($controller, $action->getMethod()))) {
                                
				$action = call_user_func_array(array($controller, $action->getMethod()), $action->getArgs());
			} else {
				$action = $this->error;
			
				$this->error = '';
			}
		} else {
			$action = $this->error;
			
			$this->error = '';
		}
		
		return $action;
	}
}

?>
