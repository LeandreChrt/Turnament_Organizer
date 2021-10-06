<?php

spl_autoload_register("Autoload");

function Autoload($class)
{
    if (substr($class, 0, 4) !== "Core") {
        $class = str_replace('\\', '/', $class);
        require "src/" . $class . ".php";
    } else {
        $class = str_replace("\\", "/", $class);
        require $class . ".php";
    }
}