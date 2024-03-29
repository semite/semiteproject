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
 * Semite Bootstrap Class
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
 * Description of Bootstrap
 *
 * @author ahmet
 */
abstract class Bootstrap {
    
    abstract function init();
    
    protected $plugins = array();
    protected $registry;
    protected $action;

    public function __construct($registry,$action) {
        $this->registry = $registry;
        $this->action = $action;
    }
    
    protected function registerPlugins(){
        
    }
}

?>
