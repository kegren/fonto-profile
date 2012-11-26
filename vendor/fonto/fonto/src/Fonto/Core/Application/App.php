<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\Application;

use Fonto\Core\Routing\Router;
use Fonto\Core\Http\Request;
use Fonto\Core\Http\Response;
use Fonto\Core\DI\Container;
use Fonto\Core\Config;
use Fonto\Core\FontoException;
use Fonto\Core\Controller;
use Fonto\Core\Url;
use Fonto\Core\View;
use Fonto\Core\Session;
use Fonto\Core\Form\Form;
use Fonto\Core\Validation\Validator;
use ActiveRecord;
use Hautelook\Phpass\PasswordHash;
use Fonto\Core\Authentication\Auth;

class App
{
	/**
	 * Current version
	 */
	const VERSION = '0.4-alpha';

	/**
	 * Default timezone
	 */
	const DEFAULT_TIMEZONE = 'Europe/Stockholm';

	/**
	 * Fonto\Core\Application\App
	 *
	 * @var object
	 */
	public $app;

	/**
	 * Fonto\Core\DI\Container
	 *
	 * @var object
	 */
	public $container;

	/**
	 * The application name
	 *
	 * @var string
	 */
	private $appName;

	/**
	 * Fonto\Core\Controller
	 *
	 * @var object
	 */
	private $controller;

	/**
	 * Storage for all routes
	 *
	 * @var array
	 */
	private $routes;

	/**
	 * Environment for the application
	 *
	 * @var string
	 */
	private $environment;

	/**
	 * Database settings
	 *
	 * @var string
	 */
	private $databaseSettings;

	/**
	 * Language for messages
	 *
	 * @var string
	 */
	private $langague = 'sv';

	public function __construct()
	{
		$this->routes = array();
		$this->controllers = array();
		$this->databaseSettings = array();
	}

	/**
	 * Sets up the application
	 */
	public function setup()
	{
		$app = $this;

		$this->registerAutoload();
		$this->setPaths();

		$this->container = new Container($app);

		$this->container['router'] = function() use ($app) {
			$router = new Router();
			$router->setApp($app);

			return $router;
		};

		$this->container['controller'] = function() use ($app) {
			$controller = new Controller\Base();
			$controller->setApp($app);

			return $controller;
		};

		$this->container['request'] = function () {
			return new Request();
		};

		$this->container['response'] = function () {
			return new Response();
		};

		$this->container['config'] = $this->container->shared(function () use ($app) {
			$config = new Config\Base(array(CONFIGPATH, APPWEBPATH, LANGPATH));
			$config->setApp($app);

			return $config;
		});

		$this->container['url'] = function() {
			return new Url();
		};

		$this->container['form'] = function() {
			return new Form();
		};

		$this->container['validator'] = function() use ($app) {
			$validator = new Validator();
			$validator->setApp($app);

			return $validator;
		};

		$this->container['view'] = function() use ($app) {
			$view = new View();
			$view->setApp($app);

			return $view;
		};

		$this->container['session'] = function() {
			return new Session\Base();
		};

		$this->container['twig'] = function() {
			$loader = new \Twig_Loader_Filesystem(VIEWPATH);
			$twig   = new \Twig_Environment($loader);

      		return $twig;
		};

		$this->container['phpass'] = function() {
			return new PasswordHash(8, false);
		};

		$this->container['auth'] = function() use ($app) {
			$auth = new Auth();
			$auth->setApp($app);

			return $auth;
		};

		$config = $this->container['config'];
		$config->load('routes', 'routes');

		$env = $config->load('app', 'environment');
		$this->setEnvironment($env);

		$timezone = $config->load('app', 'timezone');
		$this->setTimezone($timezone);

		$this->databaseSettings();
		$this->activeRecord();

		$this->setExceptionHandler(array('Fonto\Core\FontoException', 'handle'));

		return $this;
	}

	/**
	 * Runs the application.
	 */
	public function run()
	{
		try {

			$router = $this->container['router'];
			$router->setRoutes($this->routes);

			$matched = $router->match();

			if (false === $matched) {
				throw new FontoException("No route was found");
			}

			$route = $matched->run();

		} catch (FontoException $e) {
			throw $e;
		}
	}

	/**
	 * Current version
	 *
	 * @return string
	 */
	public function version()
	{
		return self::VERSION;
	}

	/**
	 * Adds routes
	 *
	 * @param  string $route
	 * @param  string $uses
	 * @return object
	 */
	public function addRoute($route, $uses)
    {
        $this->routes[$route]  = $uses;

        return $this;
    }

    /**
     * Loads ActiveRecords and sets directory for models
     *
     * @return Application
     */
    public function activeRecord()
    {
    	$config = $this->databaseSettings;
    	if ($config === false) {
    		throw new Exception("Missing database settings from application config file");
    	}

    	$type = $config['type'];
    	$host = $config['host'];
    	$user = $config['user'];
    	$pass = $config['pass'];
    	$name = $config['name'];

    	$dsn = "$type://$user:$pass@$host/$name";

     	ActiveRecord\Config::initialize(function($cfg) use($dsn)
		{
     		$cfg->set_model_directory(MODELPATH);
	    	$cfg->set_connections(array(
	    	'development' => $dsn));
 		});

 		return $this;
    }


    /**
     * Sets the application name
     *
     * @param string $name
     * @return Application
     */
	public function setAppName($name = null)
	{
		if (null === $name) {
			$this->appName = 'Demo';
		} else {
			$this->appName = ucfirst($name);
		}

		return $this;
	}

	/**
	 * Gets application name
	 *
	 * @return string Current application name
	 */
	public function getAppName()
	{
		return $this->appName;
	}

	/**
	 * Sets database settings based on what the user has specified in the config
	 *
	 * @return array
	 */
	public function databaseSettings()
	{
		$db = $this->container['config']->load('app', 'database');
		$this->databaseSettings = $db[$this->environment];

		return $this->databaseSettings;
	}

	/**
	 * Sets default timezone
	 *
	 * @param string $value
	 */
	public function setTimezone($value = null)
	{
		if (null === $value) {
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		} else {
			date_default_timezone_set($value);
		}
		return $this;
	}

	/**
	 * Returns the time zone used by this application.
	 *
	 * @return string
	 */
	public function getTimezone()
	{
		return date_default_timezone_get();
	}

	/**
	 * Returns the DI Container
	 *
	 * @return DI Container
	 */
	public function container()
	{
		return $this->container;
	}

	/**
	 * Returns the phpass service
	 */
	public function getPhpass()
	{
		return $this->container['phpass'];
	}

	/**
	 * Returns the authentication service
	 */
	public function getAuth()
	{
		return $this->container['auth'];
	}

	/**
	 * Returns the router service
	 */
	public function getRouter()
	{
		return $this->container['router'];
	}

	/**
	 * Returns the controller service
	 */
	public function getController()
	{
		return $this->container['controller'];
	}

	/**
	 * Returns the request service
	 */
	public function getRequest()
	{
		return $this->container['request'];
	}

	/**
	 * Returns the config service
	 */
	public function getConfig()
	{
		return $this->container['config'];
	}

	/**
	 * Returns the url service
	 */
	public function getUrl()
	{
		return $this->container['url'];
	}

	/**
	 * Returns the form service
	 */
	public function getForm()
	{
		return $this->container['form'];
	}

	/**
	 * Returns the validator service
	 */
	public function getValidator()
	{
		return $this->container['validator'];
	}

	/**
	 * Returns the view service
	 */
	public function getView()
	{
		return $this->container['view'];
	}

	/**
	 * Returns the session service
	 */
	public function getSession()
	{
		return $this->container['session'];
	}

	/**
	 * Returns the twig service
	 */
	public function getTwig()
	{
		return $this->container['twig'];
	}

	/**
     * Registers autoloader for this application
     *
     * @return Application
     */
	private function registerAutoload()
	{
		$loader = include VENDORPATH . 'autoload' . EXT;
		$loader->add($this->appName, APPPATH . 'src');

		return $this;
	}

	/**
	 * Sets error_reporting based on the environment
	 */
	private function setErrorReporting()
	{
		$env = $this->getEnvironment();

		switch ($env) {
			case 'development':
				error_reporting(-1);
				break;

			case 'production':
				error_reporting(0);
				break;

			default:
				throw new FontoException("$env most be either 'development' or 'production'");
				break;
		}
	}

	/**
	 * Gets environment
	 *
	 * @return string
	 */
	private function getEnvironment()
	{
		return $this->environment;
	}

	/**
	 * Sets environment for application
	 *
	 * @param string $env
	 */
	private function setEnvironment($env = null)
	{
		if (null === $env) {
			$this->environment = 'development';
		} else {
			$this->environment = $env;
		}
		return $this;
	}

	/**
	 * Sets custom exception handler
	 *
	 * @param array $options
	 */
	private function setExceptionHandler(array $options = array())
	{
		set_exception_handler($options);
	}

	/**
	 * Defines paths based on the application name
	 */
	private function setPaths()
	{
		defined('CONFIGPATH') or define('CONFIGPATH', APPPATH . 'src' . DS . $this->appName . DS . 'Config' . DS);
		defined('APPWEBPATH') or define('APPWEBPATH', APPPATH . 'src' . DS . $this->appName . DS);
		defined('LANGPATH') or define('LANGPATH', APPWEBPATH . DS . 'Language' . DS . $this->langague . DS);
		defined('CONTROLLERPATH') or define('CONTROLLERPATH', APPPATH . 'src' . DS . $this->appName . DS . 'Controllers' . DS);
		defined('VIEWPATH') or define('VIEWPATH', APPPATH . 'src' . DS . $this->appName . DS . 'Views' . DS);
		defined('MODELPATH') or define('MODELPATH', APPPATH . 'src' . DS . $this->appName . DS . 'Models' . DS);
	}
}