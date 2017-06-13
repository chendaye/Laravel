<?php

namespace App;

use Illuminate\Foundation\Auth\User as AuthentiUsers;

/**
 * 继承自带的门脸类
 * Class User
 * @package App
 */
class User extends AuthentiUsers
{
    //可注入的字段
    protected $fillable = [
        'name',
        'email',
        'password'
    ];
    //    protected $fillable = [];
}
