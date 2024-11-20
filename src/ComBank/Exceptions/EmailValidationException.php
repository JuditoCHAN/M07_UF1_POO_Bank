<?php namespace ComBank\Exceptions;


class EmailValidationException extends BaseExceptions
{
    protected $errorCode = 401;
    protected $errorLabel = 'EmailValidationException';
}
