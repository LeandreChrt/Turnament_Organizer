<?php

namespace Core;

class Router
{
    private static $routes;
    public static $redirects;
    public static function connect($url, $route)
    {
        self::$routes[$url] = $route;
    }
    public static function loadRedirect($url, $redirect)
    {
        self::$redirects[$url] = $redirect;
    }
    public static function get($url)
    {
        if (key_exists($url, self::$routes)) {
            $target = self::$routes[$url];
            $target["controller"] .= "Controller";
            $target["action"] .= "Action";
            return $target;
        } else {
            return false;
        }
    }
    public static function redirect($urlGiven, $urlRedirect)
    {
        if (in_array(substr($urlGiven, 1), self::$redirects[$urlRedirect])) {
            header("Location: " . $urlRedirect);
        }
    }
}
