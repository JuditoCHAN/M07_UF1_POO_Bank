<?php namespace ComBank\Exceptions;


class PhoneValidationException extends BaseExceptions
{
    protected $errorCode = 401;
    protected $errorLabel = 'PhoneValidationException';
}
