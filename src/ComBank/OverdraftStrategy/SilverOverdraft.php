<?php namespace ComBank\OverdraftStrategy;
      use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:39 PM
 */

/**
 * @description: Grant 100.00 overdraft funds.
 * */
class SilverOverdraft implements OverdraftInterface
{
    //private $overdraftAmount;

    public function isGrantOverdraftFunds($overdraft = 100): bool {
        //$this->overdraftAmount = $overdraft;
        return true;
    }

    public function getOverdraftFundsAmount(): float {
        //return $this->overdraftAmount;
        return 100.00;
    }
    
}
