<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('src/core/Bootstrap.class.php');

Router::factory()
->get('~^/$~', 'Index@index')
->get('~^/hello$~', function($req, $res) {
    $resp->send(View::factory()
        ->bind('title','SimCore')
        ->bind('description','Simple MVC Framework for PHP')
        ->render('hello'));
})
->run();