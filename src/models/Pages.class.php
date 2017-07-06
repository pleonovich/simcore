<?php 
/**
 * PAGES CLASS
 */

class Pages extends Model {    

    function __construct() {
        parent::__construct('data_pages');
    }

    function schema($create)
    {
        $create
		->id()
		->varchar('title')
		->text('text')
		->text('description');
    }

    function insert($insert){
        $insert
        ->set('title','test inserting')
        ->set('text','some text for testing')
        ->set('description','some description for testing');
    }


}