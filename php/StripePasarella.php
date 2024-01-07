<?php

include "./config.php";

require '../vendor/autoload.php';

if (isset($_GET['ProductID'])) {

    if (!empty($_GET['ProductID'])) {

        $SQL_PRODUCT_COUNT = $con->prepare(
            'SELECT COUNT(*) FROM '

                . $DB_TABLE['COINS_TABLE']['COINS_SCHEMA']
                . $DB_TABLE['COINS_TABLE']['COINS_TABLE']        . ' WHERE '
                . $DB_TABLE['COINS_TABLE']['COINS_ID'][$DB_TYPE] . ' = ?'

        );
        $SQL_PRODUCT_COUNT->execute([htmlentities($_GET['ProductID'])]);
        $PRODUCT_COUNT = $SQL_PRODUCT_COUNT->fetch();

        if ($PRODUCT_COUNT[0] == 1) {

            $SQL_PRODUCT = $con->prepare(
                'SELECT * FROM '

                    . $DB_TABLE['COINS_TABLE']['COINS_SCHEMA']
                    . $DB_TABLE['COINS_TABLE']['COINS_TABLE']           . ' WHERE '
                    . $DB_TABLE['COINS_TABLE']['COINS_ID'][$DB_TYPE] . ' = ?'

            );
            $SQL_PRODUCT->execute([htmlentities($_GET['ProductID'])]);

            $PRODUCT = $SQL_PRODUCT->fetch();

            \Stripe\Stripe::setApiKey($site['payments']['stripe']['secret_key']);

            header('Content-Type: application/json');

            $DOMAIN = $site['domain'];

            $checkout_session = \Stripe\Checkout\Session::create([

                'payment_method_types' => ['card', 'bancontact', 'eps', 'giropay', 'ideal'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $site['payments']['stripe']['currency'],
                        'unit_amount' =>  $PRODUCT[$DB_TABLE['COINS_TABLE']['COINS_PRICE']['BASE']] . "00",
                        'product_data' => [
                            'name' => $site['name'] . ' coins',
                            'images' => [$DOMAIN  . '/assets/img/logo.png'],
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode'          => 'payment',
                'success_url'   => $DOMAIN . '/index.php?to=coins&StripeTransactionResponse=success&ProductID=' . $PRODUCT[$DB_TABLE['COINS_TABLE']['COINS_ID']['BASE']] . '&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'    => $DOMAIN . '/index.php?to=coins&StripeTransactionResponse=cancel'
            ]);
            echo json_encode(['id' => $checkout_session->id]);
        }
    }
}
