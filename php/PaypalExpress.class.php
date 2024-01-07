<?php

class PaypalExpress {


    public function validate($paymentID, $paymentToken, $payerID, $productID, $site) {

        if($site['paypal']['mode'] == 'sandbox')
            $paypalURL = 'https://api.sandbox.paypal.com/v1/';
        else
            $paypalURL = 'https://api.paypal.com/v1/';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $paypalURL . 'oauth2/token');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $site['paypal']['id'] . ":" . $site['paypal']['secret']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        $response = curl_exec($ch);
        curl_close($ch);
        
        if(empty($response)) {

            return false;
        }

        else {

            $jsonData = json_decode($response);
            $curl     = curl_init($paypalURL . 'payments/payment/' . $paymentID);
            curl_setopt($curl, CURLOPT_POST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(

                "Authorization: Bearer " . $jsonData->access_token,
                "Accept: application/json",
                "Content-Type: application/xml"
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            
            $result = json_decode($response);
            
            return $result;
        }
    }
}