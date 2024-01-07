<?php

if(isset($_GET['article_id'])){

	$SQL_ARTICLE_COUNT = $con->prepare('SELECT COUNT(*) FROM '

		. $DB_TABLE['BLOG_TABLE']['BLOG_SCHEMA']
		. $DB_TABLE['BLOG_TABLE']['BLOG_TABLE' ] . ' WHERE '
		. $DB_TABLE['BLOG_TABLE']['BLOG_ID'    ][$DB_TYPE] . ' = ?'

	);

	$SQL_ARTICLE_COUNT->execute([ htmlentities($_GET['article_id']) ]);

	$ARTICLE_COUNT = $SQL_ARTICLE_COUNT->fetch();

	if($ARTICLE_COUNT[0] == 1) {
	
        $SQL_ARTICLE = $con->prepare('SELECT * FROM '

          . $DB_TABLE['BLOG_TABLE']['BLOG_SCHEMA' ]
          . $DB_TABLE['BLOG_TABLE']['BLOG_TABLE'  ] . ' WHERE '
          . $DB_TABLE['BLOG_TABLE']['BLOG_ID'     ][$DB_TYPE] . ' = ?'

        );
        $SQL_ARTICLE->execute([ htmlentities($_GET['article_id']) ]);

        $ARTICLE = $SQL_ARTICLE->fetch(); ?>

			<!-- ***** Banner Start ***** -->
			<div 
				class="main-banner" 
				style="background-image:
						url(./assets/images/overlay-pattern.png), 
						url(<?php echo './assets/images/blog_img/blog-' . $ARTICLE[ $DB_TABLE['BLOG_TABLE']['BLOG_ID']['BASE'] ] . '.jpg' ?>);"
				>
			  <div class="row">
			    <div class="col-lg-7">
			      <div class="header-text">
			        <h6>
			        	<em><?php echo "By: " . $ARTICLE[ $DB_TABLE['BLOG_TABLE']['BLOG_AUTHOR']['BASE'] ] . " - " . date("m/d/Y", strtotime($ARTICLE[ $DB_TABLE['BLOG_TABLE']['BLOG_DATE']['BASE'] ])) ?></em>
			        </h6>
			        <h4><?php echo $ARTICLE[ $DB_TABLE['BLOG_TABLE']['BLOG_TITLE']['BASE'] ] ?></h4>
			      </div>
			    </div>
			  </div>
			</div>
			<!-- ***** Banner End ***** -->

			<!-- ***** Join Start ***** -->
			<div class="most-popular text-light">
			  <div class="row">
			    <div class="col-lg-12">
			    	<p class="text-light">
			    		<?php echo $ARTICLE[ $DB_TABLE['BLOG_TABLE']['BLOG_BODY']['BASE'] ] ?>
			    	</p>
			    </div>
			  </div>
			</div>
			<!-- ***** Join End ***** -->

		<?php
	}

	else { echo '<h1>Article not found</h1>'; }

} else { echo '<h1>No data recived</h1>'; }

?>
<script type="text/javascript">

  $(document).ready(function() {

    $("[href='blog']").addClass('active')

  })

</script>