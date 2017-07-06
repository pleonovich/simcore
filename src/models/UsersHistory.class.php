<?php

class UsersHistory extends Model {

    function __construct(){
        parent::__construct('users_history');
    }

    function schema($create){
        $create
        ->id()
        ->int('user_id')
        ->varchar('user_ip')
        ->date('login_date')
        ->time('login_time');
    }

}