<?php

namespace Controller;

use Core\Controller;

class AppController extends Controller {
    public static function mainAction()
    {
        (new self)->render('main');
    }
}