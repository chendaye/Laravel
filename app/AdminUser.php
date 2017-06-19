<?php

namespace App;

use Illuminate\Foundation\Auth\User as AuthentiUsers;

class AdminUser extends AuthentiUsers
{
    //把这个字段设置空  不进行TOKEN验证
    protected $rememberTokenName = '';
}
