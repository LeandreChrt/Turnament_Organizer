<?php

namespace Controller;

use Core\Controller;

class ErrorController extends Controller {
    public static function errorAction()
    {
        (new self)->render('404');
    }
}