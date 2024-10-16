<?php namespace ComBank\Support\Traits;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 2:35 PM
 */

use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;

//traits: methods that can be used in multiple classes
//classes use traits, and all methods in that trait can be used if you create an object of that class
trait AmountValidationTrait
{
    /**
     * @param float $amount
     * @throws InvalidArgsException
     * @throws ZeroAmountException
     */
    public function validateAmount(float $amount):void
    {
        //si no es un numero lanzamos excepcion
        if(!is_numeric($amount)) { //comprueba si es un numero o un string numerico (ej: "32")
            throw new InvalidArgsException("Only numbers are accepted. The input was " . $amount);
        }

        //si la cantidad es 0 lanzamos otra excepcion
        if($amount <= 0) {
            throw new ZeroAmountException("The amount was 0 or less than 0, which is not accepted");
        }
    }
}
