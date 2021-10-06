<?php

namespace Core;

class Request {
    private static $get;
    private static $post;
    public function __construct()
    {
        self::$get = $this->secureArray($_GET);
        self::$post = $this->secureArray($_POST);
    }
    public static function getGet(){
        return self::$get;
    }
    public static function getPost(){
        return self::$post;
    }
    private function secureArray($array_sec){
        foreach ($array_sec as $key => $value) {
            
            if(is_array($value)) {
                $array_sec[$key] = $this->secureArray($value);
            }
            else {
                $array_sec[$key] = trim(htmlentities($value, ENT_QUOTES));
            }
        }
        return $array_sec;
    }
}