<?php

namespace App\Exceptions;

class PasswordInvalidException extends \RuntimeException
{
    protected $message = '密碼需要6位數以上，並且至少包含大寫字母、小寫字母、數字、符號各一';
}