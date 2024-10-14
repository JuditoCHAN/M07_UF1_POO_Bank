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
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;

class DepositTransaction extends BaseTransaction implements BankTransactionInterface 
{


    public function __construct($amount) {
        $this->amount = $amount;
    }

    public function applyTransaction(BackAccountInterface $bankAccount): float {
        try {
            parent::validateAmount($this->amount);
            return $bankAccount->getBalance() + $this->amount;
        } catch (InvalidArgsException $e) { 
            throw $e;
        } catch (ZeroAmountException $e) {
            throw $e;
        }
    }

    public function getTransactionInfo() {
        return "Doing transaction deposit of " . $this->amount;
    }

    public function getAmount(): float {
        return $this->amount;
    }

   
}
