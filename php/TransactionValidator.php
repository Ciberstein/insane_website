<?php

include "./config.php";

$TO = $_GET['to'];

if ($TO == 'paypal') {

    if (isset($_POST['transactionID']) && isset($_POST['productID'])) {

        if (!empty($_POST['transactionID']) && !empty($_POST['productID'])) {

            include "./PayPalPasarella.php";

            $validator = new PaypalExpress;

            $PAY_CHECK = $validator->validate($_POST['transactionID'], $site);

            if ($PAY_CHECK) {

                if ($PAY_CHECK->purchase_units[0]->amount->currency_code == $site['payments']['paypal']['currency']) {

                    $SQL_PRODUCT_COUNT = $con->prepare(
                        'SELECT COUNT(*) FROM '
                            . $DB_TABLE['COINS_TABLE']['COINS_SCHEMA']
                            . $DB_TABLE['COINS_TABLE']['COINS_TABLE'] . ' WHERE '
                            . $DB_TABLE['COINS_TABLE']['COINS_ID'][$DB_TYPE] . ' = ?'
                    );

                    $SQL_PRODUCT_COUNT->execute([htmlentities($_POST['productID'])]);
                    $PRODUCT_COUNT = $SQL_PRODUCT_COUNT->fetch();

                    if ($PRODUCT_COUNT[0] == 1) {

                        $SQL_PRODUCT = $con->prepare(
                            'SELECT * FROM '
                                . $DB_TABLE['COINS_TABLE']['COINS_SCHEMA']
                                . $DB_TABLE['COINS_TABLE']['COINS_TABLE'] . ' WHERE '
                                . $DB_TABLE['COINS_TABLE']['COINS_ID'][$DB_TYPE] . ' = ?'
                        );

                        $SQL_PRODUCT->execute([htmlentities($_POST['productID'])]);
                        $PRODUCT = $SQL_PRODUCT->fetch();

                        if (number_format($PRODUCT[$DB_TABLE['COINS_TABLE']['COINS_PRICE']['BASE']], 2, '', '.') == number_format($PAY_CHECK->purchase_units[0]->amount->value, 2, '', '.')) {

                            $SQL_CHECK_PAY = $con->prepare(
                                'SELECT COUNT(*) FROM '

                                    . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_SCHEMA']
                                    . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_TABLE'] . ' WHERE '
                                    . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_TRANS_ID'][$DB_TYPE] . ' = ?'

                            );
                            $SQL_CHECK_PAY->execute([$PAY_CHECK->id]);
                            $CHECK_PAY = $SQL_CHECK_PAY->fetch();

                            if ($CHECK_PAY[0] == 0) {

                                $SQL_PAYMENT = $con->prepare(
                                    'INSERT INTO '

                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_SCHEMA']
                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_TABLE'] . ' ('

                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_TRANS_ID'][$DB_TYPE] . ', '
                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_PAYER_EMAIL'][$DB_TYPE] . ', '
                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_PAYER_ID'][$DB_TYPE] . ', '
                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_DATE'][$DB_TYPE] . ', '
                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_TIME'][$DB_TYPE] . ', '
                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_METHOD'][$DB_TYPE] . ', '
                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_DESC'][$DB_TYPE] . ', '
                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_AMOUNT'][$DB_TYPE] . ', '
                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_CURRENCY'][$DB_TYPE] . ', '
                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_STATUS'][$DB_TYPE] . ', '
                                        . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_PRODUCT_ID'][$DB_TYPE] .

                                        ') VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )'
                                );

                                $SQL_PAYMENT->execute([

                                    htmlentities($_POST['transactionID']),
                                    $PAY_CHECK->payment_source->paypal->email_address,
                                    $_SESSION['USER_ID'],
                                    date('Y-m-d'),
                                    date('H:i:s'),
                                    'PayPal',
                                    $PRODUCT[$DB_TABLE['COINS_TABLE']['COINS_DESC']['BASE']],
                                    $PAY_CHECK->purchase_units[0]->amount->value,
                                    $PAY_CHECK->purchase_units[0]->amount->currency_code,
                                    1,
                                    $PRODUCT[$DB_TABLE['COINS_TABLE']['COINS_ID']['BASE']]

                                ]);

                                if (SendCoins($PRODUCT[$DB_TABLE['COINS_TABLE']['COINS_AMOUNT']['BASE']], $_SESSION['USER_ID']))
                                    echo '{ "icon": "success", "title": "Thanks!", "text": "Transaction \n ' . $PAY_CHECK->status . '", "refresh": true }';

                                else
                                    echo '{ "icon": "error", "title": "Error", "text": "Query exception", "refresh": false }';
                            } else echo '{ "icon": "error", "title": "Error", "text": "Product already buyed", "refresh": false }';
                        } else echo '{ "icon": "error", "title": "Error", "text": "Wrong amount", "refresh": false }';
                    } else echo '{ "icon": "error", "title": "Error", "text": "This product not exist", "refresh": false }';
                } else echo '{ "icon": "error", "title": "Error", "text": "Wrong currency", "refresh": false }';
            } else echo '{ "icon": "error", "title": "Error", "text": "Validation fail", "refresh": false }';
        } else echo '{ "icon": "error", "title": "Error", "text": "Transaction ID not obtained", "refresh": false }';
    }
}
