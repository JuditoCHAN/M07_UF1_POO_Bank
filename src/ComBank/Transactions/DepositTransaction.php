<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;

class DepositTransaction extends BaseTransaction implements BankTransactionInterface 
{

    public function __construct($amount) {
        parent::validateAmount($amount);//llamamos a la clase padre para usar el método validateAmount() del trait AmountValidation
        $this->amount = $amount;
    }

    public function applyTransaction(BackAccountInterface $bankAccount): float {
        if(parent::detectFraud($this)) {
            throw new FailedTransactionException("Deposit blocked due to possible fraud. Risk score is above 75.");
        } else {
            return $bankAccount->getBalance() + $this->amount;
        }  
    }

    public function getTransactionInfo() {
        return 'DEPOSIT_TRANSACTION';
    }
   
}
