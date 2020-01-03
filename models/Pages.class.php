<?php 
/**
 * PAGES CLASS
 */

class Pages extends Model {

    protected static $table = 'data_pages';

    protected static function schema($create)
    {
        $create
		->id()
		->varchar('title')
		->text('text')
		->text('description');
    }

    protected static function insert($insert){
        $insert
        ->set('title','test inserting')
        ->set('text','some text for testing')
        ->set('description','some description for testing');
    }


}