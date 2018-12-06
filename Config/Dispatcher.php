<?php

namespace Config;

class Dispatcher
{
    public static function dispatch(){
        $url = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
        array_shift($url);

        //check for controller
        $controller = !empty($url[0]) ? "\Controllers\\" . $url[0] . 'Controller' : '\Controllers\HomeController';

        //controller method
        $method = !empty($url[1]) ? $url[1] : 'index';

        //get argument
        $arg = !empty($url[2]) ? $url[2] : null;

        //controller instance
        $cont = new $controller;

        $cont->$method($arg);
    }
}