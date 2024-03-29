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
 * Semite home Class
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
 * Description of home
 *
 * @author ahmet
 */
class Core_ControllerHome extends Controller{
    
    public function index(){
        
        $this->load->model('core/setting');
        
        $settings = $this->model_core_setting->getSettings();
        
        $this->model_core_setting->getValue();
        
        echo '<pre>';
        print_r($this->registry);
        
        $this->data['settings'] = $settings;
        
        $this->template = 'core/home/home.tpl';
        
        $this->children = array(
            'core/header',
            'core/footer',
        );
        
        $this->response->setOutput($this->render());
    }
}

?>
