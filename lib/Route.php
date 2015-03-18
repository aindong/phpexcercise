<?php

class Route {

    protected static $routes = [];

    public static function get($url, $options)
    {
        $url = trim($url, '/');

        // Check if the option is an array
        if (is_array($options)) {
            // Validate if there is a controller and function
            if (!isset($options['uses'])) {
                throw new Exception('Trying to create a route without a controller and function');
            }
        }

        // Validate if there is a url
        if (empty($url)) {
            throw new Exception('Trying to create route without a url');
        }

        if ($url == '/') {
            $url = 'index';
        }

        // Assign to list of routes
        self::$routes[$url] = $options;
    }

    public static function start()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : 'index';

        if (!array_key_exists($url, self::$routes)) {
            throw new Exception('Page not found');
        }

        // Get the specific route
        $controller = self::$routes[$url];

        // Get the controller filename and the function name to execute
        // and explode into array
        $uses = explode('@', $controller, 2);

        // Get the controller filename and check if the file exists
        $controllerFile = 'controllers/' . $uses[0] . '.php';
        if (!file_exists($controllerFile)) {
            throw new Exception('Trying to access an unknown controller, please make sure that the ' . $controllerFile
                . ' exists on your controllers directory');
        }

        // Require the controller
        require_once($controllerFile);

        // Instantiate a new instance of the controller base on the route
        $controller = new $uses[0];

        // Check if the function exists on the controller
        if (!method_exists($controller, $uses[1])) {
            throw new Exception('Trying to call an undefined function over the '. $controllerFile);
        }

        // Execute the function
        $controller->$uses[1]();
    }
}