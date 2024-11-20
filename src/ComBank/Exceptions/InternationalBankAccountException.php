<?php namespace ComBank\Exceptions;


class InternationalBankAccountException extends BaseExceptions
{
    protected $errorCode = 401;
    protected $errorLabel = 'InternationalBankAccountException';
}
