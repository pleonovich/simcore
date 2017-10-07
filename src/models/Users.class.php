<?php
/**
 * USERS CLASS
 */

class Users extends Model
{

    function __construct()
    {
        parent::__construct('data_userlist');
    }

    function schema($create)
    {
        $create
        ->id()
        ->varchar('user_name')
        ->varchar('user_login')
        ->varchar('email')
        ->text('secret')
        ->int('manager')
        ->int('moderator');
    }

    function insert($insert)
    {
        $insert
        ->set('user_name', 'Admin')
        ->set('user_login', 'admin')
        ->set('secret', '12345')
        ->set('manager', '1')
        ->set('moderator', '1');
    }
}