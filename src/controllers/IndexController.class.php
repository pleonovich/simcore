<?php 

class IndexController extends Controller {

    public function Index () {
        View::factory()
        ->bind('title','Hello!')
        ->render('hello');
    }

}