<!-- ***** Banner Start ***** -->
<div class="main-banner">
  <div class="row">
    <div class="col-lg-7">
      <div class="header-text">
        <h6><?php echo 'Welcome to ' . $site['name'] ?></h6>
        <h4><em>The best</em> nostale private server</h4>
        <div class="main-button">
          <a href="#">Download Now</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- ***** Banner End ***** -->

<!-- ***** Latest News Start ***** -->
<div class="most-popular">
  <div class="row">
    <div class="col-lg-12">
      <div class="heading-section">
        <h4><em>Latest News</em> Right Now</h4>
      </div>
      <div class="row">
        <?php 

        $SQL_BLOG = $con->prepare('SELECT * FROM '

          . $DB_TABLE['BLOG_TABLE']['BLOG_SCHEMA' ]
          . $DB_TABLE['BLOG_TABLE']['BLOG_TABLE'  ] . ' ORDER BY '
          . $DB_TABLE['BLOG_TABLE']['BLOG_ID'     ][$DB_TYPE] . ' DESC LIMIT 4'

        );
        $SQL_BLOG->execute();

        while ($BLOG = $SQL_BLOG->fetch()) { ?>

          <div class="col-lg-6 col-sm-12">
            <div class="item blog-article" onclick="<?php echo "window.location='index.php?to=article&article_id=" . $BLOG[ $DB_TABLE['BLOG_TABLE']['BLOG_ID']['BASE'] ] . "'" ?>">
              <div 
                class="img-blogContainer"
                height="100"
                style="background-image:url(<?php echo './assets/images/blog_img/blog-' . $BLOG[ $DB_TABLE['BLOG_TABLE']['BLOG_ID']['BASE'] ] . '.jpg' ?>)"
              ></div>
              <h4>
                <?php echo substr($BLOG[ $DB_TABLE['BLOG_TABLE']['BLOG_TITLE']['BASE'] ], 0, 20) . '...' ?>
                <br>
                <span><?php echo substr($BLOG[ $DB_TABLE['BLOG_TABLE']['BLOG_BODY']['BASE'] ], 0, 30) . '...' ?></span>
              </h4>
              <ul>
                <li><i class="fa fa-star"></i> <?php echo $BLOG[ $DB_TABLE['BLOG_TABLE']['BLOG_AUTHOR']['BASE'] ] ?></li>
                <li><i class="fa fa-calendar-days"></i> <?php echo date("m/d/Y", strtotime($BLOG[ $DB_TABLE['BLOG_TABLE']['BLOG_DATE']['BASE'] ])) ?></li>
              </ul>
            </div>
          </div>

        <?php } ?>
        <div class="col-lg-12">
          <div class="main-button">
            <a href="blog">View more</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- ***** Latest News End ***** -->

<script type="text/javascript">

  $(document).ready(function() {

    $("[href='index']").addClass('active')

  })

</script>