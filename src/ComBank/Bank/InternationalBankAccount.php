<?php namespace ComBank\Bank;

use ComBank\Exceptions\InternationalBankAccountException;


class InternationalBankAccount extends BankAccount {
    
    public function __construct($balance) {
        parent::__construct($balance);
        $this->currency = " $ (USD)";
    }
    
    
    public function getConvertedBalance(): float {
        try {
            //usas el trait que importa la clase padre
            $convertedBalance = parent::convertBalance($this->getBalance());
            return round($convertedBalance, 2);
        } catch (InternationalBankAccountException $e) {
            throw $e;
        }
    }


    public function getConvertedCurrency(): string {
        return " $ (USD)";
    }

}