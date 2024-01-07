<script src="<?php echo "https://www.paypal.com/sdk/js?client-id=" . $site['payments']['paypal']['id'] . "&currency=" . $site['payments']['paypal']['currency'] ?>"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
<script src="https://js.stripe.com/v3/"></script>

<?php
if (isset($_GET['StripeTransactionResponse'])) {

  if (!empty($_GET['StripeTransactionResponse'])) {

    if ($_GET['StripeTransactionResponse'] == 'success' && isset($_GET['session_id']) && isset($_GET['ProductID'])) {

      if (!empty($_GET['session_id'])) {

        require './vendor/autoload.php';

        $stripe = new \Stripe\StripeClient($site['payments']['stripe']['secret_key']);
        $checkout_session = $stripe->checkout->sessions->retrieve($_GET['session_id'], []);

        echo json_encode($checkout_session->customer_details->email);

        if (!empty($_GET['ProductID'])) {

          $SQL_PRODUCT_COUNT = $con->prepare(
            'SELECT COUNT(*) FROM '

              . $DB_TABLE['COINS_TABLE']['COINS_SCHEMA']
              . $DB_TABLE['COINS_TABLE']['COINS_TABLE'] . ' WHERE '
              . $DB_TABLE['COINS_TABLE']['COINS_ID'][$DB_TYPE] . ' = ?'
          );

          $SQL_PRODUCT_COUNT->execute([htmlentities($_GET['ProductID'])]);
          $PRODUCT_COUNT = $SQL_PRODUCT_COUNT->fetch();

          if ($PRODUCT_COUNT[0] == 1) {

            $SQL_CHECK_PAY = $con->prepare(
              'SELECT COUNT(*) FROM '

                . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_SCHEMA']
                . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_TABLE'] . ' WHERE '
                . $DB_TABLE['PAYMENTS_TABLE']['PAYMENTS_TRANS_ID'][$DB_TYPE] . ' = ?'

            );
            $SQL_CHECK_PAY->execute([$checkout_session->id]);
            $CHECK_PAY = $SQL_CHECK_PAY->fetch();

            if ($CHECK_PAY[0] == 0) {

              $SQL_PRODUCT = $con->prepare(
                'SELECT * FROM '

                  . $DB_TABLE['COINS_TABLE']['COINS_SCHEMA']
                  . $DB_TABLE['COINS_TABLE']['COINS_TABLE'] . ' WHERE '
                  . $DB_TABLE['COINS_TABLE']['COINS_ID'][$DB_TYPE] . ' = ?'

              );

              $SQL_PRODUCT->execute([htmlentities($_GET['ProductID'])]);
              $PRODUCT = $SQL_PRODUCT->fetch();

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
                $checkout_session->id,
                $checkout_session->customer_details->email,
                $_SESSION['USER_ID'],
                date('Y-m-d'),
                date('H:i:s'),
                'Stripe',
                $PRODUCT[$DB_TABLE['COINS_TABLE']['COINS_DESC']['BASE']],
                substr($checkout_session->amount_total, 0, -2),
                strtoupper($checkout_session->currency),
                1,
                $PRODUCT[$DB_TABLE['COINS_TABLE']['COINS_ID']['BASE']]

              ]);

              if (SendCoins($PRODUCT[$DB_TABLE['COINS_TABLE']['COINS_AMOUNT']['BASE']], $_SESSION['USER_ID']))

                echo "<script>Swal.fire({title:'Transaction completed',icon:'success',confirmButtonText:'OK',allowOutsideClick:false,allowEscapeKey:false}).then((result) => { window.location='./coins' });</script>";

              else echo "<script>Swal.fire({title:'Query error',icon:'error',confirmButtonText:'OK',allowOutsideClick:false,allowEscapeKey:false}).then((result) => { window.location='./coins' });</script>";
            } else echo "<script>Swal.fire({title:'Product already buyed',icon:'error',confirmButtonText:'OK',allowOutsideClick:false,allowEscapeKey:false}).then((result) => { window.location='./coins' });</script>";
          } else echo "<script>Swal.fire({title:'This product not exist',icon:'error',confirmButtonText:'OK',allowOutsideClick:false,allowEscapeKey:false}).then((result) => { window.location='./coins' });</script>";
        } else echo "<script>Swal.fire({title:'Product ID is missing',icon:'error',confirmButtonText:'OK',allowOutsideClick:false,allowEscapeKey:false}).then((result) => { window.location='./coins' });</script>";
      } else echo "<script>Swal.fire({title:'Session ID is missing',icon:'error',confirmButtonText:'OK',allowOutsideClick:false,allowEscapeKey:false}).then((result) => { window.location='./coins' });</script>";
    }
    if ($_GET['StripeTransactionResponse'] == 'cancel') {
      echo "<script>Swal.fire({title:'Transaction cancelled',icon:'error',confirmButtonText:'OK',allowOutsideClick:false,allowEscapeKey:false});</script>";
    }
  }
}
?>

<!-- ***** Banner Start ***** -->
<div class="main-banner_4">
  <div class="row">
    <div class="col-lg-7">
      <div class="header-text">
        <h6>Get your coins at the best price</h6>
        <h4><em><?php echo $site['name'] ?></em> coins</h4>
      </div>
    </div>
  </div>
</div>
<!-- ***** Banner End ***** -->

<!-- ***** Coins Start ***** -->
<div class="most-popular">
  <div class="row">
    <div class="col-lg-12">
      <div class="heading-section">
        <h4><em>Coins</em> shop</h4>
      </div>
      <div class="row">
        <?php

        $SQL_COINS = $con->prepare(
          'SELECT * FROM '

            . $DB_TABLE['COINS_TABLE']['COINS_SCHEMA']
            . $DB_TABLE['COINS_TABLE']['COINS_TABLE'] . ' ORDER BY '
            . $DB_TABLE['COINS_TABLE']['COINS_ID'][$DB_TYPE] . ' ASC'

        );
        $SQL_COINS->execute();

        while ($COINS = $SQL_COINS->fetch()) { ?>

          <div class="col-lg-4 col-sm-12">
            <div class="item coin-item">
              <div class="img-blogContainer" style="background-image:url(./assets/images/coins.png); background-size: contain; height: 200px;"></div>
              <h4>
                <?php echo $COINS[$DB_TABLE['COINS_TABLE']['COINS_AMOUNT']['BASE']] . " Coins" ?>
                <br>
                <span><?php echo $COINS[$DB_TABLE['COINS_TABLE']['COINS_DESC']['BASE']] ?></span>
              </h4>
              <ul>
                <li><?php echo $COINS[$DB_TABLE['COINS_TABLE']['COINS_PRICE']['BASE']] . " €" ?></li>
                <li></li>
              </ul>
              <div class="d-flex flex-column gap-2 mt-4">
                <div class="w-100 btn-stripe" id="<?php echo 'paypal-button-' . $COINS[$DB_TABLE['COINS_TABLE']['COINS_ID']['BASE']]; ?>"></div>
                <button class="btn btn-stripe" onclick="StripeGateway(<?php echo $COINS[$DB_TABLE['COINS_TABLE']['COINS_ID']['BASE']] ?>)" id="<?php echo "STRP__" . $COINS[$DB_TABLE['COINS_TABLE']['COINS_ID']['BASE']] ?>">
                  <i class="fa-brands fa-stripe"></i>
                </button>
              </div>

            </div>
          </div>

          <script>
            paypal.Buttons({
              style: {
                layout: 'vertical',
                color: 'gold',
                shape: 'rect',
                tagline: false,
                label: 'paypal',
                size: 'medium'
              },
              fundingSource: paypal.FUNDING.PAYPAL,
              createOrder: (data, actions) => {
                return actions.order.create({
                  purchase_units: [{
                    amount: {
                      value: '<?php echo $COINS[$DB_TABLE['COINS_TABLE']['COINS_PRICE']['BASE']] ?>'
                    }
                  }],
                  application_context: {
                    shipping_preference: 'NO_SHIPPING'
                  }
                });
              },

              onApprove: (data, actions) => {
                return actions.order.capture().then(function(orderData) {

                  const transaction = orderData.purchase_units[0].payments.captures[0];

                  $.ajax({
                    type: 'POST',
                    url: './php/TransactionValidator.php?to=paypal',
                    data: {
                      transactionID: orderData.id,
                      productID: <?php echo $COINS[$DB_TABLE['COINS_TABLE']['COINS_ID']['BASE']] ?>
                    },
                    success: function(data) {

                      let obj = JSON.parse(data)

                      console.log(data)

                      Swal.fire(obj).then(() => {

                        if (obj.refresh) {
                          window.location = 'coins'
                        }
                      });
                    },
                    error: function(data) {
                      let obj = JSON.parse(data)
                      Swal.fire({
                        title: obj.text,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                      }).then((result) => {
                        location.reload();
                      });
                    }
                  });
                });
              },

              onCancel: (data, actions) => {
                Swal.fire({
                  title: 'Transaction cancelled',
                  icon: 'error',
                  confirmButtonText: 'OK',
                  allowOutsideClick: false,
                  allowEscapeKey: false
                });
              },

              onError: function(error) {
                return alert(error);
              }
            }).render('<?php echo '#paypal-button-' . $COINS[$DB_TABLE['COINS_TABLE']['COINS_ID']['BASE']]; ?>');
          </script>

        <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- ***** Coins End ***** -->


<script type="text/javascript">
  StripeGateway = id => {

    const stripe = Stripe("<?php echo $site['payments']['stripe']['publishable_key']; ?>");

    fetch(`./php/StripePasarella.php?ProductID=${id}`, {
        method: "POST",
      })
      .then(response => {
        return response.json();
      })
      .then(session => {
        return stripe?.redirectToCheckout({
          sessionId: session.id
        });
      })
      .then(res => {
        if (result.error) {
          alert(res.error.message);
        }
      })
  }


  $(document).ready(function() {

    $("[href='coins']").addClass('active')

  })
</script>