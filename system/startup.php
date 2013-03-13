<?php

/**
 * @package     Engine_Core
 * @version     $Id: {$name}.php 9764 2012-08-17 00:04:31Z ahmet $
 * @copyright   Copyright (c) 2008 Semite Project
 * @license     http://www.semiteproject.com/license/
 */

// Session Path
session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../temporary/session'));

// Register Globals
if (ini_get('register_globals')) {
	ini_set('session.use_cookies', 'On');
	ini_set('session.use_trans_sid', 'Off');
		
	session_set_cookie_params(0, '/');
	session_start();
	
	$globals = array($_REQUEST, $_SESSION, $_SERVER, $_FILES);

	foreach ($globals as $global) {
		foreach(array_keys($global) as $key) {
			unset(${$key}); 
		}
	}
}

// Magic Quotes Fix
if (ini_get('magic_quotes_gpc')) {
	function clean($data) {
   		if (is_array($data)) {
  			foreach ($data as $key => $value) {
    			$data[clean($key)] = clean($value);
  			}
		} else {
  			$data = stripslashes($data);
		}
	
		return $data;
	}			
	
	$_GET = clean($_GET);
	$_POST = clean($_POST);
	$_REQUEST = clean($_REQUEST);
	$_COOKIE = clean($_COOKIE);
}

if (!ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

// Windows IIS Compatibility  
if (!isset($_SERVER['DOCUMENT_ROOT'])) { 
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['PATH_TRANSLATED'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['REQUEST_URI'])) { 
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1); 
	
	if (isset($_SERVER['QUERY_STRING'])) { 
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; 
	} 
}

if (!isset($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

require_once APPLICATION_PATH_SYS.DS.'libraries/Application.php';

$namespaces = array(
    'Engine'    => APPLICATION_PATH_LIB . DS . 'Engine',
);

$application = new Application($namespaces);
$application->_initStartup();
$application->_run();
?>
