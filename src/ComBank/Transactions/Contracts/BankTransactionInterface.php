<?php namespace ComBank\Transactions\Contracts;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:29 PM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;

interface BankTransactionInterface
{
    const BLOCKED = 'TRANSACTION_BLOCKED';
    const ALLOWED = 'TRANSACTION_ALLOWED';

    public function applyTransaction(BackAccountInterface $bankAccount);

    public function getTransactionInfo();

    public function getAmount();
}
