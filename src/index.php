<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Bank\NationalBankAccount;
use ComBank\Bank\Person;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Exceptions\InvalidOverdraftFundsException;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    
    // show balance account
    $bankAccount1 = new BankAccount(400); //al crearla pone el status a STATUS_OPEN y el overdraft como NoOverdraft
    pl('Balance of my account: ' . $bankAccount1->getBalance());


    // close account
    $bankAccount1->closeAccount();
    pl('My account is now closed.');

    // reopen account
    $bankAccount1->reopenAccount();
    pl('My account is now reopened.');


    // deposit +150 
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new DepositTransaction(150));
    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());


    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new WithdrawTransaction(25));


    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    
    // withdrawal -600
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new WithdrawTransaction(600));

} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (InvalidOverdraftFundsException $e) { //añadido pq sino da error al lanzar esta excepcion en la linea 57
    pl($e->getMessage());
} catch (InvalidArgsException $e) { //añadido por si se crea cuenta (o se hace transaccion) con argumento no valido
    pl($e->getMessage());
}  catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
    pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());
}





//---[Bank account 2]---/
echo "<br><br>";
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {
    
    // show balance account
   $bankAccount2 = new BankAccount(200);
   $bankAccount2->applyOverdraft(new SilverOverdraft());
   pl('Balance of my account: ' . $bankAccount2->getBalance());

    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new DepositTransaction(100));
    
    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(300));
   
    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(50));
    
    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(120));
    
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (InvalidArgsException $e) {
    pl($e->getMessage());
}


if(isset($bankAccount2)) { //para comprobar si se ha creado cuenta (en caso de haberla intentado crear antes con parametro no valido no se habrá creado)
    try {
        pl('Doing transaction withdrawal (-20) with current balance : ' . 
        $bankAccount2->getBalance());
        $bankAccount2->transaction(new WithdrawTransaction(20));
        
        pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());

        $bankAccount2->closeAccount();  //intentar cerrar cuenta ya cerrada, salta error
        pl('My account is now closed.');

        $bankAccount2->closeAccount();
    } catch (FailedTransactionException $e) {
        pl('Error transaction: ' . $e->getMessage());
    } catch (BankAccountException $e) {
        pl($e->getMessage());
    }
}




//----------------------------------TESTING NATIONAL AND INTERNATIONAL ACCOUNTS-----------------
echo "<br><br>";
pl('--------- [Start testing national account (No conversion)] --------');

$nationalBankAccount1 = new NationalBankAccount(500);
pl("My balance: " . $nationalBankAccount1->getBalance() . $nationalBankAccount1->getCurrency());


//----------------------------------TESTING CURRENCY CONVERSION----------------------------------
echo "<br><br>";
pl('--------- [Start testing international account (Dollar conversion)] --------');

$p = new Person("Joe Doe", 111999900002222, "john_doe@gmail.com");
$internationalBankAccount1 = new InternationalBankAccount(310);

pl("My balance: " . $internationalBankAccount1->getBalance() . $internationalBankAccount1->getCurrency());

try {
    pl("My balance: " . $internationalBankAccount1->getConvertedBalance() . $internationalBankAccount1->getConvertedCurrency());
} catch(Exception $e) {
    pl('Error: ' . $e->getMessage());
}




//----------------------------------TESTING EMAIL VALIDATION----------------------------------
echo "<br><br>";
pl('--------- [Start testing national account with VALID EMAIL] --------');

$newHolder = new Person("Juan Ortega", "4546777723321111", "juan1995@gmail.com");

$nationalBankAccount1->setHolder($newHolder);

pl('Validating email: ' . $nationalBankAccount1->getHolder()->getEmail());

// try {
//     $emailValidation = $nationalBankAccount1->getHolder()->getEmailValidation();
//     pl($emailValidation);
// } catch (Exception $e) {
//     pl('Error: ' . $e->getMessage());
// }


echo "<br><br>";
pl('--------- [Start testing international account with INVALID EMAIL] --------');

$internationalBankAccount1->setHolder(new Person("Carla Sanchez", "1000200030004000", "carla.san@gmial.com"));

pl('Validating email: ' . $internationalBankAccount1->getHolder()->getEmail());

// try {
//     sleep(1); //la API solo permite 1 petición/segundo
//     $emailValidationFail = $internationalBankAccount1->getHolder()->getEmailValidation();
//     pl($emailValidationFail);
// } catch(Exception $e) {
//     pl('Error: ' . $e->getMessage());
// }





//----------------------------------TESTING FRAUD DETECTION----------------------------------
echo "<br><br>";
pl('--------- [Start testing FRAUD DETECTION IN TRANSACTIONS] --------');
pl('--------- [Allowing DEPOSIT] --------');
try {
    $nationalBankAccount2 = new NationalBankAccount(400);
    pl('Balance of my account: ' . $nationalBankAccount2->getBalance());

    pl('Doing transaction deposit (+5000) with current balance ' . $nationalBankAccount2->getBalance());
    $nationalBankAccount2->transaction(new DepositTransaction(5000));

    pl('My new balance after deposit (+5000) : ' . $nationalBankAccount2->getBalance());
} catch(Exception $e) {
    pl($e->getMessage());
}

echo "<br><br>";
pl('--------- [Blocking DEPOSIT] --------');
try {
    pl('Balance of my account: ' . $nationalBankAccount2->getBalance());

    pl('Doing transaction deposit (+30000) with current balance ' . $nationalBankAccount2->getBalance());
    $nationalBankAccount2->transaction(new DepositTransaction(30000));

    pl('My new balance after deposit (+30000) : ' . $nationalBankAccount2->getBalance());
} catch(Exception $e) {
    pl($e->getMessage());
}


echo "<br><br>";
pl('--------- [Allowing WITHDRAWAL] --------');
try {
    $internationalBankAccount2 = new NationalBankAccount(10000);
    pl('Balance of my account: ' . $internationalBankAccount2->getBalance());

    pl('Doing transaction withdrawal (-1200) with current balance ' . $internationalBankAccount2->getBalance());
    $internationalBankAccount2->transaction(new WithdrawTransaction(1200));

    pl('My new balance after withdraw (-1200) : ' . $internationalBankAccount2->getBalance());
} catch(Exception $e) {
    pl($e->getMessage());
}

echo "<br><br>";
pl('--------- [Blocking WITHDRAWAL] --------');
try {
    pl('Balance of my account: ' . $internationalBankAccount2->getBalance());

    pl('Doing transaction withdrawal (-5000) with current balance ' . $internationalBankAccount2->getBalance());
    $internationalBankAccount2->transaction(new WithdrawTransaction(5000));

    pl('My new balance after withdraw (-5000) : ' . $internationalBankAccount2->getBalance());
} catch(Exception $e) {
    pl($e->getMessage());
}