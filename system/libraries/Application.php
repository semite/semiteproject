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
 * Semite Application Class
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
 * Description of Application
 *
 * @author ahmet
 */
class Application {

    protected $nameSpaces;
    protected $registry;
    protected $response;
    protected $config;
    protected $request;

    public function __construct($namespaces) {

        $this->nameSpaces = $namespaces;
    }

    public function _initStartup() {
        foreach ($this->nameSpaces as $namespace) {
            if (!is_dir($namespace)) {
                continue;
            } else {
                $files = glob($namespace . DS . '*.php');

                if ($files) {
                    foreach ($files as $file) {
                        $class = basename($file, '.php');
                        require_once $namespace . DS . $class . EXT;
                    }
                }
            }
        }

        // Error Reporting
        error_reporting(E_ALL);
        
        // Registry
        $this->registry = $registry = new Registry();

        // Loader
        $loader = new Loader($registry);
        $registry->set('load', $loader);

        // Config
        $this->config = $config = new Config();
        $registry->set('config', $config);

        // Database 
        $db = new Database(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        // Settings
        $query = $db->query("SELECT * FROM " . DB_PREFIX . "core_setting ORDER BY `group` ASC");

        foreach ($query->rows as $setting) {
            if (!$setting['serialized']) {
                $config->set($setting['key'], $setting['value']);
            } else {
                $config->set($setting['key'], unserialize($setting['value']));
            }
        }

        $config->set('config_url', HTTP_SERVER);
        $config->set('config_ssl', HTTPS_SERVER);

        // Url
        $url = new Url($config->get('config_url'), $config->get('config_secure') ? $config->get('config_ssl') : $config->get('config_url'));
        $registry->set('url', $url);

        // Log 
        $log = new Log($config->get('config_error_filename'));
        $registry->set('log', $log);

        // get general config
        if (file_exists(APPLICATION_PATH_SET . DS . 'general.php') && !$config->get('config_enviroment')) {
            $generalConfig = include APPLICATION_PATH_SET . DS . 'general.php';
        } else {
            $generalConfig = array('environment_mode' => $config->get('config_enviroment'));
        }


        // development mode
        $application_env = @$generalConfig['environment_mode'];
        defined('APPLICATION_ENV') || define('APPLICATION_ENV', (
                        !empty($_SERVER['_ENGINE_ENVIRONMENT']) ? $_SERVER['_ENGINE_ENVIRONMENT'] : (
                                $application_env ? $application_env :
                                        'production'
                        )));
        
        $this->error_log = $config->get('config_error_log');

        function error_handler($errno, $errstr, $errfile, $errline) {

            switch ($errno) {
                case E_NOTICE:
                case E_USER_NOTICE:
                    $error = 'Notice';
                    break;
                case E_WARNING:
                case E_USER_WARNING:
                    $error = 'Warning';
                    break;
                case E_ERROR:
                case E_USER_ERROR:
                    $error = 'Fatal Error';
                    break;
                default:
                    $error = 'Unknown';
                    break;
            }


                if (defined('APPLICATION_ENV')) {
                    switch (APPLICATION_ENV) {
                        case 'development':
                            echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
                            break;

                        case 'testing':
                        case 'production':
                            error_reporting(0);
                            break;

                        default:
                            exit('The application environment is not set correctly.');
                    }
                }

//            if ($this->error_log) {
//                $log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
//            }

            return true;
        }

        // Request
        $this->request = $request = new Request();
        $registry->set('request', $request);


        // Cache
        $loader->library('Cache');
        $cache = new Cache();
        $registry->set('cache', $cache);

        // Session
        $loader->library('Session');
        $session = new Session();
        $registry->set('session', $session);

        // Language Detection
        $languages = array();

        $query = $db->query("SELECT * FROM `" . DB_PREFIX . "core_language` WHERE status = '1'");

        foreach ($query->rows as $result) {
            $languages[$result['code']] = $result;
        }

        $detect = '';

        if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && $request->server['HTTP_ACCEPT_LANGUAGE']) {
            $browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);

            foreach ($browser_languages as $browser_language) {
                foreach ($languages as $key => $value) {
                    if ($value['status']) {
                        $locale = explode(',', $value['locale']);

                        if (in_array($browser_language, $locale)) {
                            $detect = $key;
                        }
                    }
                }
            }
        }

        if (isset($session->data['language']) && array_key_exists($session->data['language'], $languages) && $languages[$session->data['language']]['status']) {
            $code = $session->data['language'];
        } elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages) && $languages[$request->cookie['language']]['status']) {
            $code = $request->cookie['language'];
        } elseif ($detect) {
            $code = $detect;
        } else {
            $code = $config->get('config_language');
        }

        if (!isset($session->data['language']) || $session->data['language'] != $code) {
            $session->data['language'] = $code;
        }

        if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {
            setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
        }

        $config->set('config_language_id', $languages[$code]['language_id']);
        $config->set('config_language', $languages[$code]['code']);

        // Language	
        $loader->library('Language');
        $language = new Language($languages[$code]['directory']);
        $language->load($languages[$code]['filename']);
        $registry->set('language', $language);

        // Document
        $loader->library('Document');
        $document = new Document();
        $registry->set('document', $document);
        
               
        
    }

    public function _run() {
        // Error Handler
        set_error_handler('error_handler');
        
        // Response
        $response = new Response();
        $response->addHeader('Content-Type: text/html; charset=utf-8');
        $response->setCompression($this->config->get('config_compression'));
        $this->registry->set('response', $response); 
        
        // Front Controller 
        $controller = new Front($this->registry);
        
        // SEO URL's
        $controller->addPreAction(new Action('core/seo_url'));	

        // Maintenance Mode
        $controller->addPreAction(new Action('core/maintenance'));

        // Router
        if (isset($this->request->get['route'])) {
                $action = new Action($this->request->get['route']);
        } else {
                $action = new Action('core/home');
        }

        // Dispatch
        $controller->dispatch($action, new Action('core/error'));

        // Output
        $response->output();

    }

}


?>
