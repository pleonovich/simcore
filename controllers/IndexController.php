<?php 

use SimCore\core\View as View;

class IndexController {

    public function index ($req, $resp) {
        $resp->send(View::factory()
        ->bind('title','SimCore')
        ->bind('description','Simple MVC Framework for PHP')
        ->render('hello'));
    }

}