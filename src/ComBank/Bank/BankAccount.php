<?php namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FraudDetectionException;
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
    use AmountValidationTrait, ApiTrait;

    protected $balance;
    protected $status;
    protected $overdraft;
    protected $currency;
    protected $holder;


    public function __construct($balance) {
        $this->validateAmount($balance); //si no lanza excepcion sigue
        $this->balance = round($balance, 2);
        $this->status = BackAccountInterface::STATUS_OPEN;
        $this->overdraft = new NoOverdraft();
        $this->currency = " € (EUR)";
        $this->holder = new Person("DefaultUser", "1111222233334444"); //persona por defecto, en index.php se hace setPersona
    }


    public function transaction(BankTransactionInterface $transaction) { //le pasamos como parametro un obj DepositTransaction o WithdrawTransaction
        if(!$this->openAccount()) {
            throw new BankAccountException("You cannot perform a transaction in a closed account.");
        } else {
            try {
                $this->balance = $transaction->applyTransaction($this);
            } catch (FraudDetectionException | InvalidOverdraftFundsException $e) {
                throw new FailedTransactionException("Error: " . $e->getMessage());
            }
        }
    }


    //Comprueba que si la cuenta esta abierta o cerrada
    public function openAccount () { 
        return $this->status === BackAccountInterface::STATUS_OPEN;
    }


    //if the account is already open, it should throw an exception
    public function reopenAccount () {
        if($this->openAccount()) {
            throw new BankAccountException("The account is already open. You cannot reopen it");
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


    public function getCurrency() {
        return $this->currency;
    }


    public function setCurrency($newCurrency) {
        $this->currency = $newCurrency;
    }


    public function getHolder() {
        return $this->holder;
    }


    public function setHolder(Person $p) {
        $this->holder = $p;
    }


    }
