<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once ('src/core/Bootstrap.class.php');

Router::factory()
->set('~^/$~', 'Index', 'index')
->set('~^/id/([0-9]+)$~', 'Index', 'index', array('id'))
->run();