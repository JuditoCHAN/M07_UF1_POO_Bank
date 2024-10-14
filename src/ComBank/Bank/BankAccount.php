<?php namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class BankAccount implements BackAccountInterface
{
    private $balance;
    private $status;
    private $overdraft;


    public function __construct($balance, $status, $overdraft) {
        $this->balance = $balance;
        $this->overdraft = $overdraft;
        $this->openAccount();
    }


    public function transaction(BankTransactionInterface $transaction) {

    }


    public function openAccount () {
        $this->status = true;
    }


    public function reopenAccount () {
        if(!$this->status) {
            $this->status = true;
        }
    }
 

    public function closeAccount () {
        $this->status = false;
    }
 
    public function getBalance (): float {
        return $this->balance;
    }
 
    // public function getOverdraft() {

    // }
 
    // public function getOverdraft(OverdraftInterface $overdraft) {

    // }
 
    public function applyOverdraft(OverdraftInterface $overdraft) {

    }
 
    public function setBalance($newBalance) {
        $this->balance = $newBalance;
    }


    }
