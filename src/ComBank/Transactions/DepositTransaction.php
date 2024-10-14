<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class DepositTransaction extends BaseTransaction implements BankTransactionInterface 
{


    public function __construct() {
        
    }

    public function applyTransaction(BackAccountInterface $bankAccount): float {
        try {
            parent::validateAmount($this->amount);
        } catch (InvalidArgsException $e) { 

        }
        return $bankAccount->getBalance() + $this->amount;
    }

    public function getTransactionInfo() {
        return "Doing transaction deposit of " . $this->amount;
    }

    public function getAmount(): float {
        return $this->amount;
    }

   
}
