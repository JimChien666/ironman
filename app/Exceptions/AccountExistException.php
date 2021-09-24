<?php

namespace App\Exceptions;

class AccountExistException extends \RuntimeException
{

    protected $message = "帳號重複註冊";
}