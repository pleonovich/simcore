<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('vendor/autoload.php');
require_once('src/core/Bootstrap.php');
SimCore\core\Bootstrap::config(__DIR__, array(
    'src/config/%s.php',
    'src/core/%s.php',
    'src/objects/%s.php',
    'src/lib/%s.php',
    '/models/%s.php',
    '/modules/%s.php',
    '/controllers/%s.php'
    ));


use SimCore\core\Router as Router;
use SimCore\core\View as View;

Router::factory()
->get('~^/$~', 'Index@index')
// ->get('~^/hello$~', function($req, $res) {
//     $res->send(View::factory()
//         ->bind('title','SimCore')
//         ->bind('description','Simple MVC Framework for PHP')
//         ->render('hello'));
// })
->run();