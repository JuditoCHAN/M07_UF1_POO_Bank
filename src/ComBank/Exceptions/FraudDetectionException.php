<?php namespace ComBank\Exceptions;


class FraudDetectionException extends BaseExceptions
{
    protected $errorCode = 401;
    protected $errorLabel = 'FraudDetectionException';
}
