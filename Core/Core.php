<?php

namespace Core;

class Core
{
    private static $jsonFile;
    private static $language;
    private static $arrayLanguage = ["fr", "en"];
    public function __construct()
    {
        new Request;
    }
    public function run()
    {
        require "src/routes.php";
        $arrayTarget = Router::get(substr($_SERVER['REQUEST_URI'], 0, 1) . substr($_SERVER['REQUEST_URI'], 4));
        if (!is_array($arrayTarget)) {
            $arrayTarget = ["controller" => "ErrorController", "action" => "errorAction"];
        }
        $URI = explode("/", substr($_SERVER['REQUEST_URI'], 1));
        if (!in_array($URI[0], self::$arrayLanguage)) {
            if (in_array(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2), self::$arrayLanguage)) {
                $URI[0] = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
                header("Location: /" . implode("/", $URI));
            } else {
                $this->setLanguage("en");
            }
        } else {
            $this->setLanguage($URI[0]);
        }
        $temp = file_get_contents(__DIR__ . '/../webroot/js/File.json');
        $this->setJsonFile(json_decode($temp));
        $targetController = "Controller\\" . ucfirst($arrayTarget["controller"]);
        $action = $arrayTarget["action"];
        $targetController::$action();
    }

    public function setLanguage($language)
    {
        self::$language = $language;
    }
    public static function getLanguage()
    {
        return self::$language;
    }

    public function setJsonFile($json)
    {
        self::$jsonFile = $json;
    }
    public static function getJsonFile()
    {
        return self::$jsonFile;
    }
}
