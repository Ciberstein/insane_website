<link href="./assets/css/roulette/roulette.css?r=5" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>

<!-- ***** Banner Start ***** -->
<div class="main-banner_6">
  <div class="row">
    <div class="col-lg-7">
      <div class="header-text">
        <h6>Try your luck and win!</h6>
        <h4><em><?php echo $site['name'] ?></em> Roulette</h4>
      </div>
    </div>
  </div>
</div>
<!-- ***** Banner End ***** -->

<div id="wrap" class="roulette_container position-relative">
  <div class="roulette-price-container">
    <div><?php echo "Spin price: " . $site['wheel']['price'] . " Coins" ?></div>
  </div>
  <div id="gameContainer">
    <div class="board_start obj"><img src="http://img.babathe.com/upload/specialDisplay/htmlImage/2019/test/coupon_button.png" class="join"></div>
    <div class="board_bg obj"><img src="http://img.babathe.com/upload/specialDisplay/htmlImage/2019/test/roulette_circle_bg.png"></div>
    <div class="board_on obj"></div>
    <div class="board_arrow obj"><img src="http://img.babathe.com/upload/specialDisplay/htmlImage/2019/test/roulette_board_arrow.png"></div>
  </div>
</div>

<script src="./assets/js/roulette/index.js?r=3"></script>

<script type="text/javascript">
  $(document).ready(function() {

    $("[href='roulette']").addClass('active')

  })
</script>