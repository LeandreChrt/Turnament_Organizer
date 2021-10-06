<?php

use Core\Router;

Router::connect('/', ['controller' => 'app', 'action' => 'main']);
Router::connect('/tournament', ['controller' => 'app', 'action' => 'tournament']);