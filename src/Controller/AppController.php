<?php

namespace Controller;

use Core\Controller;
use Core\Request;
use Model\AppModel;

class AppController extends Controller
{
    public static function mainAction()
    {
        (new self)->render('main');
    }
    public static function newAction()
    {
        $post = Request::getPost();
        if (!isset($post["type"])){
            ErrorController::errorAction();
        } else {
            $tournament = new AppModel;
            $tournament->newTournament($post["type"], $post["nombreParticipants"]);
        }
    }
}
