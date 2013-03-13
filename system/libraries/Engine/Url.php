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
 * Semite Url Class
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
 * Description of Url
 *
 * @author ahmet
 */
class Url {

    private $url;
    private $ssl;
    private $rewrite = array();

    public function __construct($url, $ssl = '') {
        $this->url = $url;
        $this->ssl = $ssl;
    }

    public function addRewrite($rewrite) {
        $this->rewrite[] = $rewrite;
    }

    public function link($route, $args = '', $connection = 'NONSSL') {
        if ($connection == 'NONSSL') {
            $url = $this->url;
        } else {
            $url = $this->ssl;
        }

//        $url .= 'index.php?route=' . $route;
        $url .='?route=' . $route;

        if ($args) {
            $url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
        }

        foreach ($this->rewrite as $rewrite) {
            $url = $rewrite->rewrite($url);
        }

        return $url;
    }

}

?>
