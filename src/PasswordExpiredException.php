<?php

namespace Dmn\Exceptions;

class PasswordExpiredException extends Exception
{
    protected $httpStatusCode = 403;

    protected $code = 'password_expired';

    public $message = 'Your password already expired. Please change or waive your password.';
}
