<?php namespace ComBank\Bank;

use ComBank\Support\Traits\ApiTrait;
use PHPUnit\TextUI\XmlConfiguration\Validator;
use ComBank\Exceptions\EmailValidationException;

class Person {

    use ApiTrait;
    private $name;
    private $idCard;
    private $email;
    private $phone;

    public function __construct($name, $idCard) {
        // if(!$this->validateEmail($email)) {
        //     throw new EmailValidationException("Invalid email address: " . $email);
        // } else {
            $this->name = $name;
            $this->idCard = $idCard;
        //}
    }


    public function setEmail($email) {
        if($this->validateEmail($email)) {
            return "Email is valid.";
        } else {
            return "Invalid email address.";
        }
    }


    public function setPhone($phone) {
        if($this->validatePhone($phone)) {
            return "Phone is valid.";
        } else {
            return "Invalid phone.";
        }
    }


    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
    
    public function setIdCard($idCard) {
        $this->idCard = $idCard;
    }

    public function getIdCard() {
        return $this->idCard;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

}