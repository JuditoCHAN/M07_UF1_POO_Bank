<?php namespace ComBank\Support\Traits;

use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Transactions\WithdrawTransaction;

trait ApiTrait {

    public function validateEmail(String $email): bool {
        return true;
    }

    public function convertBalance(float $balance): float {
        return 0.0;
    }

    public function detectFraud(BankTransactionInterface $bankTransactionInterface): bool {
        return false;
    }
}