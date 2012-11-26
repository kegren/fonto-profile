<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\Routing;

use Fonto\Core\FontoException;
use Fonto\Core\Request;
use Fonto\Core\Application\App;

class Router
{
    const ACTION_PREFIX        = 'Action';
    const CONTROLLER_NAMESPACE = '\\Controllers';
    const DEFAULT_ROUTE        = '/';
    const ROUTE_DELIMITER      = '#';
    const DEFAULT_CONTROLLER   = 'home';
    const DEFAULT_ACTION       = 'indexAction';

    /**
     * Patterns for routes
     *
     * @var array
     */
    private $patterns = array(
        '(:num)' => '(\d+)',
        '(:action)' => '([\w\_\-\%]+)'
    );

    /**
     * Registered routes
     *
     * var array
     */
    private $routes;

    /**
     * Registered controllers
     *
     * var array
     */
    private $controllers;

    /**
     * Controller
     *
     * @var string
     */
    public $controller;

    /**
     * Action
     *
     * @var string
     */
    private $action;

    /**
     * Parameters
     *
     * @var string
     */
    private $parameters;

    /**
     * Fonto\Core\Application\App
     *
     * @var object
     */
    protected $app;


    public function __construct()
    {
        $this->routes = array();
        $this->parameters = array();
    }

    /**
     * Sets the current application
     *
     * @param App $app
     */
    public function setApp(App $app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Sets routes
     *
     * @param array $routes
     */
    public function setRoutes($routes = array())
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * Returns routes
     *
     * @return routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Routes the request
     *
     * @return mixed
     */
    public function run()
    {
        $ns = $this->app->getAppName() . self::CONTROLLER_NAMESPACE;
        $class = $ns . '\\' . ucfirst($this->getController());
        $file  = CONTROLLERPATH . ucfirst($this->getController()) . EXT;

        if (!file_exists($file) or (!is_readable($file))) {
            throw new FontoException("The file $file was not found");
        }

        if (!class_exists($class)) {
            throw new FontoException("The class $class does not exist");
        }

        $object = new $class;
        $object->setApp($this->app);

        if (method_exists($object, $this->action)) {
            if (!empty($this->parameters)) {
                call_user_func_array(array($object, $this->action), $this->parameters);
            } else {
                call_user_func(array($object, $this->action));
            }
        } else {
            throw new FontoException("Class: $class does not contain action: $this->action");
        }
    }

    /**
     * Matches the current request with registered routes
     *
     * @return mixed
     */
    public function match()
    {
        $parsedUriStr = $this->app->getRequest()->getRequestUri();
        $parsedUriArr = explode('/', $parsedUriStr);
        $parsedUriArr = array_filter($parsedUriArr);

        list($num, $action) = array_keys($this->patterns);
        list($rNum, $rAction) = array_values($this->patterns);

        foreach ($this->routes as $route => $uses) {

            if ($route == '<:controller>') {
                if (!empty($parsedUriArr)) {
                    if ($parsedUriArr[1] == $uses) {
                        return $this->map($uses, $parsedUriArr);
                    }
                }
            }

            if ($route == $parsedUriStr) {
                return $this->map($uses, null, true);
            }

           $route = str_replace(array($num,$action), array($rNum,$rAction), $route);

            if (preg_match('@^' . $route . '$@', $parsedUriStr, $return)) {
                if (!empty($return[0])) {
                    unset($return[0]);
                    return $this->map($uses, $return, true);
                }
            }
        }

        return false;
    }

    /**
     * Maps a route/uri to controller, action and parameters
     *
     * @return Router
     */
    public function map($route, $uri = null, $routeReg = false)
    {
        if ($routeReg) {
            $delimit = strpos($route, self::ROUTE_DELIMITER) and $delimit = self::ROUTE_DELIMITER;
            $route   = explode($delimit, $route);

            $controller = !empty($route[0]) and $controller = $route[0];
            $action     = !empty($route[1]) and $action = $route[1];
            $parameters = !empty($uri[2]) and $parameters = $uri;

            $this->setController($controller)
                 ->setAction($action)
                 ->setParameters($parameters);

            return $this;
        }

        $controller = $route;
        $action     = !empty($uri[2]) ? $action = $uri[2] : 'index';
        unset($uri[1], $uri[2]);

        $this->setController($controller)
             ->setAction($action);

        if (!empty($uri[3])) {
            $this->setParameters($uri);
        }

        return $this;
    }

    /**
     * Sets controller
     *
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Returns controller
     *
     * @return controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Sets action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action . self::ACTION_PREFIX;

        return $this;
    }

    /**
     * Returns action
     *
     * @return action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets parameters
     *
     * @param string $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Returns parameters
     *
     * @return parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}