<?php namespace ComBank\Support\Traits;

use ComBank\Exceptions\EmailValidationException;
use ComBank\Exceptions\FraudDetectionException;
use ComBank\Exceptions\PhoneValidationException;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\InternationalBankAccountException;


trait ApiTrait {

    public function validateEmail(String $email): bool {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://emailvalidation.abstractapi.com/v1/?api_key=30eea2e4c2424106b624dd2f8656045a&email=$email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => false, //para que me deje hacer peticion
            CURLOPT_SSL_VERIFYHOST => false //sino da error por certificado SSL
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new EmailValidationException("Error" . $err);
        } else {
            //comprobar si es DELIVERABLE, si es UNDELIVERABLE o UNKNOWN devolver false
            $result = json_decode($response, true);

            //json_decode devuelve null si hay algun error
            if($result != null) {
                //var_dump($result);
                return ($result["deliverability"] == "DELIVERABLE") ? true : false;
            } else {
                throw new EmailValidationException("Error al decodificar el JSON al hacer la petición a la API para validar email");
            }
        }
    }

    

    /**
     * Devuelve el valor de un euro en dolares
     * @param float $balance
     * @throws \ComBank\Exceptions\InternationalBankAccountException
     * @return float
     */
    public function convertBalance(float $balance): float {
        $ch = curl_init();
        $api = "https://latest.currency-api.pages.dev/v1/currencies/eur.json";
        
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true, //lo devuelve como un string
            CURLOPT_HTTPHEADER => array(
                // "Authorization: Bearer $accesToken", //HEader de autorización con el token
                "Content-Type: application/json", //el contenido enviado es JSON
                "Accept: application/json" //se espera JSON en la respuesta
            ),
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $jsonResult = curl_exec($ch); //devuelve false si la solicitud falla

        if(!$jsonResult) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new InternationalBankAccountException("Error al hacer la petición a la API: " . $error);
        } else {
            //json_decode puede devolver objeto genérico de clase stdClass o
            //un array asociativo si indicamos true en el segundo param
            $result = json_decode($jsonResult, true);
            curl_close($ch);

            //json_decode devuelve null si hay algun error
            if($result != null) { 
                $valorEnDolares = round($result["eur"]["usd"], 2); //1ro convierte string a num, despues lo redondea a 2 decimales
                return $valorEnDolares * $balance;
            } else {
                throw new InternationalBankAccountException("Error al decodificar el JSON al hacer la petición a la API para convertir el balance");
            }
        }
        
    }


    
    public function detectFraud(BankTransactionInterface $transaction): bool {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://673b7323339a4ce4451c4131.mockapi.io/api/v1/Fraude",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $jsonResult = curl_exec($curl);

        if($jsonResult) {
            $result = json_decode($jsonResult, true);

            curl_close($curl);

            if($result != null) { 
                foreach($result as $data) {
                    if($transaction->getTransactionInfo() == $data["transactionType"]) {
                        if($transaction->getAmount() < $data["amount"]) {
                            return ($data["result"] == BankTransactionInterface::BLOCKED) ? true : false;
                        }
                    }
                }
                throw new FraudDetectionException("Error al verificar la transacción en la API de detección de fraude");
            } else {
                throw new FraudDetectionException("Error al decodificar el JSON al hacer la petición a la API de detección de fraude");
            }
        } else {
            $error = curl_error($curl);
            curl_close($curl);

            throw new FraudDetectionException("Error al realizar la petición a la API de detección de fraude: " . $error);
        }
    }



    public function validatePhone(String $phoneNumber): bool {
        if(is_numeric($phoneNumber)) {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://phonevalidation.abstractapi.com/v1/?api_key=89be84e2d70c47c4901c8cc7afdd00f4&phone=$phoneNumber&country=ES",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false, //para que me deje hacer peticion
                CURLOPT_SSL_VERIFYHOST => false //sino da error por certificado SSL
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl); //devuelve "" si no hay error

            curl_close($curl);

            if ($err) {
                throw new PhoneValidationException("Error: " . $err);
            } else {
                $result = json_decode($response, true);

                if($result != null) {
                    try {
                        //var_dump($result);
                        return ($result["valid"] == "true") ? true : false; 
                        
                    } catch(\Exception $e) {
                        throw new PhoneValidationException("Error al acceder al JSON decodificado.");
                    }
                } else {
                    throw new PhoneValidationException("Error al decodificar el JSON al hacer la petición a la API para validar el telefono");
                }
            }
        } else {
            throw new PhoneValidationException("Debes pasar un número de teléfono correcto (solo numeros).");
        }
    }
}