<?php

class UsersHistory extends Model {

    protected static $table = 'users_history';

    protected static function schema($create){
        $create
        ->id()
        ->int('user_id')
        ->varchar('user_ip')
        ->date('login_date')
        ->time('login_time');
    }

}