<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <?php include "./php/config.php"; ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <title><?php echo $site['name'] ?></title>

  <!-- Bootstrap core CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <!-- Additional CSS Files -->
  <link rel="stylesheet" href="assets/css/fontawesome.css">
  <link rel="stylesheet" href="assets/css/templatemo-cyborg-gaming.css?r=5">
  <link rel="stylesheet" href="assets/css/owl.css">
  <link rel="stylesheet" href="assets/css/animate.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>

  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader End ***** -->

  <!-- ***** Header Area Start ***** -->
  <?php include "./includes/header.php" ?>
  <!-- ***** Header Area End ***** -->

  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="page-content">
          <?php

          if (isset($_SESSION['USER_RANGE'])) $RANGE = $_SESSION['USER_RANGE'];
          else $RANGE = 0;

          if ($site['status'] || $RANGE >= $WEB_ROLES['GAME_ADMIN']) {

            if (isset($_GET['to'])) $TO = $_GET['to'];
            else $TO = 'home';

            if ($TO == 'home') {
              include "./pages/home.php";
            }

            if ($TO == 'blog') {
              include "./pages/blog.php";
            }

            if ($TO == 'article') {
              include "./pages/article.php";
            }

            if (!isset($_SESSION['USER_ID']) || $_SESSION['USER_ID'] == NULL) { // Pre-login

              if ($TO == 'join') {
                include "./pages/join.php";
              }
            } else { // Login 

              if ($TO == 'logout') {
                session_destroy();
                header("Location: ./index");
              } elseif ($TO == 'profile') {
                include "./pages/profile.php";
              } elseif ($TO == 'shop') {
                include "./pages/shop.php";
              } elseif ($TO == 'coins') {
                include "./pages/coins.php";
              } elseif ($TO == 'cart') {
                include "./pages/cart.php";
              } elseif ($TO == 'roulette') {
                include "./pages/roulette.php";
              }
            }
          } else { ?>

            <div class="text-center text-light">
              <h1>We are under maintenance</h1>
              <h5>Be right back soon!</h5>
            </div>

          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <?php include './includes/footer.php' ?>

  <!-- Scripts -->

  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/tabs.js"></script>
  <script src="assets/js/popup.js"></script>
  <script src="assets/js/custom.js"></script>

</body>

</html>