<?php namespace ComBank\Bank;

use ComBank\Support\Traits\ApiTrait;

class Person {

    use ApiTrait;
    private $name;
    private $idCard;
    private $email;

    public function __construct($name, $idCard, $email) {
        $this->name = $name;
        $this->idCard = $idCard;
        $this->name = $email;
    }
}