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
use ComBank\Support\Traits\ApiTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use PHPUnit\TextUI\XmlConfiguration\Validator;

class BankAccount implements BackAccountInterface
{
    use AmountValidationTrait;

    use ApiTrait;
    protected $balance;
    protected $status;
    protected $overdraft;
    protected $currency;
    protected $holder;


    public function __construct($balance, $currency = "", Person $holder = new Person("User", 1111222233334444, "user@gmail.com")) {
        $this->validateAmount($balance); //si no lanza excepcion sigue
        $this->balance = $balance;
        $this->status = BackAccountInterface::STATUS_OPEN;
        $this->overdraft = new NoOverdraft();
        $this->currency = $currency;
        $this->holder = $holder;
        
    }


    public function transaction(BankTransactionInterface $transaction) { //le pasamos como parametro un obj DepositTransaction o WithdrawTransaction
        if(!$this->openAccount()) {
            throw new BankAccountException("You cannot perform a transaction in a closed account.");
        } else {
            $this->balance = $transaction->applyTransaction($this); // applyTransaction devuelve el balance final
        }
    }


    //Comprueba que si la cuenta esta abierta o cerrada
    public function openAccount () { 
        return $this->status === BackAccountInterface::STATUS_OPEN;
    }


    //if the account is already open, it should throw an exception
    public function reopenAccount () {
        if($this->openAccount()) {
            throw new BankAccountException("The account is already open. You cannot reope ");
        } else {
            $this->status = BackAccountInterface::STATUS_OPEN;
        }
    }
 

    public function closeAccount () {
        if(!$this->openAccount()) {
            throw new BankAccountException("Error: Account is already closed.");
        } else {
            $this->status = BackAccountInterface::STATUS_CLOSED;
        } 
    }
 

    public function getBalance (): float {
        return $this->balance;
    }
 

    public function getOverdraft(): OverdraftInterface {
        //return $this->overdraft->getOverdraftFundsAmount();
        return $this->overdraft;
    }
 
 
    public function applyOverdraft(OverdraftInterface $overdraft) {
        $this->overdraft = $overdraft;
    }
 
    
    public function setBalance($newBalance) {
        $this->balance = $newBalance;
    }


    }
