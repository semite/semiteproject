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
 * Semite Cache Class
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
 * Description of Cache
 *
 * @author ahmet
 */
class Cache {

    private $expire = 3600;
    
    public function test(){
        echo __METHOD__;
    }

    public function get($key) {
        $files = glob(APPLICATION_PATH_TMP.DS . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

        if ($files) {
            $cache = file_get_contents($files[0]);

            $data = unserialize($cache);

            foreach ($files as $file) {
                $time = substr(strrchr($file, '.'), 1);

                if ($time < time()) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }

            return $data;
        }
    }

    public function set($key, $value) {
        $this->delete($key);

        $file = APPLICATION_PATH_TMP.DS . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);

        $handle = fopen($file, 'w');

        fwrite($handle, serialize($value));

        fclose($handle);
    }

    public function delete($key) {
        $files = glob(APPLICATION_PATH_TMP.DS . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }

}

?>
