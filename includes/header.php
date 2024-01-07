<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="index" class="logo">
                        <img src="assets/images/logo.png" alt="">
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <?php if (!isset($_SESSION['USER_ID']) || $_SESSION['USER_ID'] == NULL) {  ?>

                        <ul class="nav">
                            <li><a href="index"><i class="fas fa-home"></i> Home</a></li>
                            <li><a href="blog"><i class="fas fa-newspaper"></i> Blog</a></li>
                            <li><a href="join">Login / Register <img src="assets/images/user.png" alt=""></a></li>
                        </ul>

                    <?php } else {

                        $SQL_PROFILE = $con->prepare(
                            'SELECT * FROM '

                                . $DB_TABLE['USER_TABLE']['USER_SCHEMA']
                                . $DB_TABLE['USER_TABLE']['USER_TABLE'] . ' WHERE '
                                . $DB_TABLE['USER_TABLE']['USER_ID'][$DB_TYPE] . ' = ?'

                        );

                        $SQL_PROFILE->execute([$_SESSION['USER_ID']]);

                        $PROFILE = $SQL_PROFILE->fetch(); ?>

                        <ul class="nav">
                            <li><a href="index"><i class="fas fa-home"></i> Home</a></li>
                            <li><a href="blog"><i class="fas fa-newspaper"></i> Blog</a></li>
                            <li><a href="shop"><i class="fas fa-store"></i> Shop</a></li>
                            <li><a href="roulette"><i class="fas fa-award"></i> Roulette</a></li>
                            <li><a href="coins"><i class="fas fa-coins"></i> Coins <span class="badge bg-secondary"><?php echo $PROFILE[$DB_TABLE['USER_TABLE']['USER_BALANCE']['BASE']] ?></span></a></li>
                            <li>
                                <a href="profile"><?php echo $_SESSION['USER_NICK'] ?>
                                    <img src="<?php echo './assets/images/profile_pics/' .  $PROFILE[$DB_TABLE['USER_TABLE']['USER_PROFILE_PIC']['BASE']] . '.png' ?>" alt="">
                                </a>
                            </li>
                        </ul>

                    <?php } ?>
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>