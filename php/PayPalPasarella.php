<?php

class PaypalExpress
{

    public function validate($transaction_ID, $site)
    {

        if ($site['payments']['paypal']['mode'] == 'sandbox')
            $paypalURL = 'https://api.sandbox.paypal.com/';
        else
            $paypalURL = 'https://api.paypal.com/';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $paypalURL . 'v1/oauth2/token');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $site['payments']['paypal']['id'] . ":" . $site['payments']['paypal']['secret']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        $response = curl_exec($ch);
        curl_close($ch);

        if (!empty($response)) {

            $jsonData = json_decode($response);
            $curl     = curl_init($paypalURL . 'v2/checkout/orders/' . $transaction_ID);
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
        } else return false;
    }
}
