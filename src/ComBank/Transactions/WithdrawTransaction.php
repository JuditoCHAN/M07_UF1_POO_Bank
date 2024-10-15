<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Exceptions\FailedTransactionException;

class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{

    public function __construct($amount) {
        $this->amount = $amount;
    }

    public function applyTransaction(BackAccountInterface $bankAccount): float {
        try {
            parent::validateAmount($this->amount);
            
            //habrá que tener en cuenta overdraft si la resta sale negativa
            $balanceTotal = $bankAccount->getBalance() - $this->amount;
            
            if($balanceTotal < 0) {
                $overdraftAmount = $bankAccount->getOverdraft()->getOverdraftFundsAmount();
                if($overdraftAmount > 0) { //si tiene overdraft será mayor a 0 (la clase NoOverdraft devuelve 0)
                    if($overdraftAmount >= (-$balanceTotal)) {
                        return $balanceTotal;
                    } else {
                        throw new FailedTransactionException("The overdraft is not enough for this transaction.");
                    }
                    
                } else {
                    throw new FailedTransactionException("This bank account doesn't have overdraft.");
                }
            } else { //si la resta no sale negativa se puede hacer el withdraw
                return $balanceTotal;
            }
            
        } catch (InvalidArgsException $e) {
            throw $e;
        } catch (ZeroAmountException $e) {
            throw $e;
        }
        
    }

    public function getTransactionInfo(): string {
        return "Doing transaction withdrawal of " . $this->amount;
    }

    public function getAmount(): float {
        return $this->amount;
    }
   
}
