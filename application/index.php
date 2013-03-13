<?php

/**
 * @package     Engine_Core
 * @version     $Id: index.php 9764 2012-08-17 00:04:31Z ahmet $
 * @copyright   Copyright (c) 2008 Semite Project
 * @license     http://www.semiteproject.com/license/
 */

// Start trace
if( !empty($_SERVER['_ENGINE_TRACE_ALLOW']) && extension_loaded('xdebug') ) {
  xdebug_start_trace();
} else if( !empty($_SERVER['_ENGINE_XHPROF_ALLOW']) && extension_loaded('xhprof') ) {
  xhprof_enable();
}

// Rewrite detection
if( !defined('_ENGINE_R_REWRITE') && 'cli' !== PHP_SAPI ) {
  $target = null;
  if( empty($_GET['rewrite']) && 0 !== strpos($_SERVER['REQUEST_URI'], $_SERVER['PHP_SELF']) ) {
    // Redirect to index if rewrite not enabled
    $target = $_SERVER['PHP_SELF'];
    $params = $_GET;
    unset($params['rewrite']);
    if( !empty($params) ) {
      $target .= '?' . http_build_query($params);
    }
  } else if( isset($_GET['rewrite']) && $_GET['rewrite'] == 2 ) {
    // Redirect to virtual index if rewrite enabled
    $target = str_replace($_SERVER['PHP_SELF'], dirname($_SERVER['PHP_SELF']), $_SERVER['REQUEST_URI']);
  }
  if( null !== $target ) {
    header('Location: ' . $target);
    exit();
  }
}

// Basic setup
error_reporting(E_ALL);

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('PS') || define('PS', PATH_SEPARATOR);
defined('_ENGINE') || define('_ENGINE', true);
defined('_ENGINE_REQUEST_START') || 
    define('_ENGINE_REQUEST_START', microtime(true));

defined('APPLICATION_PATH') || 
    define('APPLICATION_PATH',     realpath(dirname(dirname(__FILE__))));
defined('APPLICATION_PATH_COR') || 
    define('APPLICATION_PATH_COR', realpath(dirname(__FILE__)));
defined('APPLICATION_PATH_SYS') || 
    define('APPLICATION_PATH_SYS',     realpath(dirname(dirname(__FILE__))).DS.'system');
defined('APPLICATION_PATH_EXT') || 
    define('APPLICATION_PATH_EXT', APPLICATION_PATH . DS . 'externals');
defined('APPLICATION_PATH_PUB') || 
    define('APPLICATION_PATH_PUB', APPLICATION_PATH . DS . 'public');
defined('APPLICATION_PATH_TMP') || 
    define('APPLICATION_PATH_TMP', APPLICATION_PATH . DS . 'temporary');
defined('APPLICATION_PATH_LIB') || 
    define('APPLICATION_PATH_LIB', APPLICATION_PATH_SYS . DS . 'libraries');

defined('APPLICATION_PATH_BTS') || 
    define('APPLICATION_PATH_BTS', APPLICATION_PATH_COR . DS . 'bootstraps');
defined('APPLICATION_PATH_MOD') || 
    define('APPLICATION_PATH_MOD', APPLICATION_PATH_COR . DS . 'modules');
defined('APPLICATION_PATH_PLU') || 
    define('APPLICATION_PATH_PLU', APPLICATION_PATH_COR . DS . 'plugins');
defined('APPLICATION_PATH_SET') || 
    define('APPLICATION_PATH_SET', APPLICATION_PATH_COR . DS . 'settings');
defined('APPLICATION_PATH_WID') || 
    define('APPLICATION_PATH_WID', APPLICATION_PATH_COR . DS . 'widgets');

defined('APPLICATION_NAME') || define('APPLICATION_NAME', 'Core');
defined('_ENGINE_ADMIN_NEUTER') || define('_ENGINE_ADMIN_NEUTER', false);
defined('_ENGINE_NO_AUTH') || define('_ENGINE_NO_AUTH', false);
defined('_ENGINE_SSL') || define('_ENGINE_SSL', (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on'));

define('EXT','.php');

define('HTTPS_SERVER', 'https://'.$_SERVER['HTTP_HOST'].DS);
define('HTTP_SERVER',  'http://'.$_SERVER['HTTP_HOST'].DS);


// Check for uninstalled state
if( !file_exists(APPLICATION_PATH_SET . DS . 'database.php') ) {
  if( 'cli' !== PHP_SAPI ) {
    header('Location: ' . rtrim((string)constant('_ENGINE_R_BASE'), '/') . '/install/index.php');
  } else {
    echo 'Not installed' . PHP_EOL;
  }
  exit();
} else {
    require_once APPLICATION_PATH_SET . DS . 'database.php';
}


// Startup
require_once(APPLICATION_PATH_SYS.DS . 'startup.php');



?>
