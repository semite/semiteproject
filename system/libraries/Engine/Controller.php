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
 * Semite Controller Class
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
 * Description of Controller
 *
 * @author ahmet
 */
abstract class Controller {

    protected $registry;
    protected $id;
    protected $layout;
    protected $template;
    protected $module;
    protected $children = array();
    protected $data = array();
    protected $output;

    public function __construct($registry) {
        
               
        $this->registry = $registry;
        
    }

    public function __get($key) {
        return $this->registry->get($key);
    }

    public function __set($key, $value) {
        $this->registry->set($key, $value);
    }

    protected function forward($route, $args = array()) {
        return new Action($route, $args);
    }

    protected function redirect($url, $status = 302) {
        header('Status: ' . $status);
        header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
        exit();
    }

    protected function getChild($child, $args = array()) {
        $action = new Action($child, $args);


        if (file_exists($action->getFile())) {
            require_once($action->getFile());

            $class = $action->getClass();
            
            $controller = new $class($this->registry);

            $controller->{$action->getMethod()}($action->getArgs());



            return $controller->output;
        } else {
            trigger_error('Error: Could not load controller ' . $child . '!');
            exit();
        }
    }

    protected function render() {

        $parts = explode('/', str_replace('../', '', (string) $this->template));

        $this->template = ucfirst($parts[0]) . DS . 'views' . DS . (isset($parts[1]) ? $parts[1] : 'home') . DS . (isset($parts[2]) ? $parts[2] : 'index');
               
        $this->run_bootstrap($parts);

        foreach ($this->children as $child) {
            $this->data[basename($child)] = $this->getChild($child);
        }

        if (file_exists(APPLICATION_PATH_MOD . DS . ucfirst($this->template))) {
            extract($this->data);

            ob_start();

            require(APPLICATION_PATH_MOD . DS . ucfirst($this->template));

            $this->output = ob_get_contents();

            ob_end_clean();

            return $this->output;
        } else {
            trigger_error('Error: Could not load template ' . APPLICATION_PATH_MOD . DS . ucfirst($this->template) . '!');
            exit();
        }
    }

    protected function run_bootstrap($parts) {


        $bootstrap_file = APPLICATION_PATH_MOD . DS . ucfirst($parts[0]) . DS . 'bootstrap' . EXT;

        if (file_exists($bootstrap_file)) {

            require_once $bootstrap_file;

            $class = ucfirst($parts[0]) . '_Bootstrap';

            $bootstrap = new $class($this->registry,$parts);

            $methods = get_class_methods($class);

            foreach ($methods as $method) {
                if ($method =='__construct') {
                    continue;
                } else {
                    $bootstrap->$method();
                }
            }
        }
    }

}

?>
