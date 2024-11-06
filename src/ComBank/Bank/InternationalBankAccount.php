<?php namespace ComBank\Bank;


class InternationalBankAccount extends BankAccount {
    
    public function getConvertedBalance(): float {
        return 0.0;
    }

    public function getConvertedCurrency(): string {
        $ch = curl_init();
        $api = "https://latest.currency-api.pages.dev/v1/currencies/eur.json";
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array("Your access token"),
            CURLOPT_SSL_VERIFYPEER => false
        ));
        $jsonResult = curl_exec($ch);

        if(!$jsonResult) {
            $error = curl_error($ch);
            curl_close($ch);
            return "There was an error: " . $error;
        } else {
            $result = json_decode($jsonResult);
            curl_close($ch);
            return "Converting balance to Dollars (Rate: 1 USD = " . $result . " €";
        }
        
    }
}