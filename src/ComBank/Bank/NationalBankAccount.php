<?php namespace ComBank\Bank;


class NationalBankAccount extends BankAccount {

    public function __construct($balance) {
        parent::__construct($balance);
    }
}