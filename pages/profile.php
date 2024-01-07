<!-- ***** Banner Start ***** -->
<div class="row">
  <div class="col-lg-12">
    <div class="main-profile ">
      <div class="row">
        <div class="col-lg-4">
          <img src="<?php echo './assets/images/profile_pics/' .  $PROFILE[$DB_TABLE['USER_TABLE']['USER_PROFILE_PIC']['BASE']] . '.png' ?>" alt="" style="border-radius: 23px;">
        </div>
        <div class="col-lg-4 align-self-center">
          <div class="main-info header-text">
            <span>Online</span>
            <h4><?php echo $_SESSION['USER_NICK'] ?></h4>
            <p>This is your space, here you can manage your account.</p>
            <div class="main-border-button">
              <a href="logout">Logout</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 align-self-center">
          <ul>
            <li>IP Address <span><?php echo $PROFILE[$DB_TABLE['USER_TABLE']['USER_IP']['BASE']] ?></span></li>
            <li>Account Range <span><?php echo $PROFILE[$DB_TABLE['USER_TABLE']['USER_RANGE']['BASE']] ?></span></li>
            <li>Account ID <span><?php echo $PROFILE[$DB_TABLE['USER_TABLE']['USER_ID']['BASE']] ?></span></li>
          </ul>
        </div>
      </div>
      <!--
      <div class="row">
        <div class="col-lg-12">
          <div class="clips">
            <div class="row">
              <div class="col-lg-12">
                <div class="heading-section">
                  <h4><em>Your</em> Characters</h4>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="item">
                  <div class="thumb">
                    <img src="assets/images/clip-01.jpg" alt="" style="border-radius: 23px;">
                    <a href="https://www.youtube.com/watch?v=r1b03uKWk_M" target="_blank"><i class="fa fa-play"></i></a>
                  </div>
                  <div class="down-content">
                    <h4>First Clip</h4>
                    <span><i class="fa fa-eye"></i> 250</span>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="item">
                  <div class="thumb">
                    <img src="assets/images/clip-02.jpg" alt="" style="border-radius: 23px;">
                    <a href="https://www.youtube.com/watch?v=r1b03uKWk_M" target="_blank"><i class="fa fa-play"></i></a>
                  </div>
                  <div class="down-content">
                    <h4>Second Clip</h4>
                    <span><i class="fa fa-eye"></i> 183</span>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="item">
                  <div class="thumb">
                    <img src="assets/images/clip-03.jpg" alt="" style="border-radius: 23px;">
                    <a href="https://www.youtube.com/watch?v=r1b03uKWk_M" target="_blank"><i class="fa fa-play"></i></a>
                  </div>
                  <div class="down-content">
                    <h4>Third Clip</h4>
                    <span><i class="fa fa-eye"></i> 141</span>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="item">
                  <div class="thumb">
                    <img src="assets/images/clip-04.jpg" alt="" style="border-radius: 23px;">
                    <a href="https://www.youtube.com/watch?v=r1b03uKWk_M" target="_blank"><i class="fa fa-play"></i></a>
                  </div>
                  <div class="down-content">
                    <h4>Fourth Clip</h4>
                    <span><i class="fa fa-eye"></i> 91</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      -->
    </div>
  </div>
</div>
<!-- ***** Banner End ***** -->
<script type="text/javascript">
  $(document).ready(function() {

    $("[href='profile']").addClass('active')

  })
</script>