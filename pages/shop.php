<!-- ***** Banner Start ***** -->
<div class="main-banner_5">
  <div class="row">
    <div class="col-lg-7">
      <div class="header-text">
        <h6><?php echo $site['name'] . ' web shop' ?></h6>
        <h4><em>Buy</em> your favorite items here</h4>
      </div>
    </div>
  </div>
</div>
<!-- ***** Banner End ***** -->

<?php

if (isset($_GET['category'])) {

  $CTG = htmlentities($_GET['category']);
  $SQL_SHOP = $con->prepare(
    'SELECT * FROM '
      . $DB_TABLE['SHOP_TABLE']['SHOP_SCHEMA']
      . $DB_TABLE['SHOP_TABLE']['SHOP_TABLE'] . ' WHERE '
      . $DB_TABLE['SHOP_TABLE']['SHOP_CATEGORY'][$DB_TYPE] . ' = ?'
  );

  $SQL_SHOP->execute([$CTG]);
} else {

  $CTG = 0;
  $SQL_SHOP = $con->prepare(
    'SELECT * FROM '
      . $DB_TABLE['SHOP_TABLE']['SHOP_SCHEMA']
      . $DB_TABLE['SHOP_TABLE']['SHOP_TABLE']
  );
  $SQL_SHOP->execute();
}
?>

<!-- ***** Latest News Start ***** -->
<div class="most-popular">
  <div class="row">
    <div class="col-lg-12">
      <div class="d-flex justify-content-between gap-4">
        <div class="heading-section">
          <h4><em>Shop</em> items</h4>
        </div>
        <div>
          <div class="input-group">
            <label for="selectCategory" class="input-group-text" id="basic-addon1">
              <i class="fa-solid fa-filter"></i>
            </label>
            <select class="form-control form-control-lg" id="selectCategory" name="selectCategory">
              <option value="" disabled selected>Select category</option>
              <?php

              $SQL_CATEGORY = $con->prepare(
                'SELECT * FROM '
                  . $DB_TABLE['SHOP_CATEGORY_TABLE']['SHOP_CATEGORY_SCHEMA']
                  . $DB_TABLE['SHOP_CATEGORY_TABLE']['SHOP_CATEGORY_TABLE']
              );
              $SQL_CATEGORY->execute();

              while ($CATEGORY = $SQL_CATEGORY->fetch()) { ?>
                <option <?php if ($CTG == $CATEGORY[$DB_TABLE['SHOP_CATEGORY_TABLE']['SHOP_CATEGORY_ID']['BASE']]) echo "selected" ?> value="<?php echo $CATEGORY[$DB_TABLE['SHOP_CATEGORY_TABLE']['SHOP_CATEGORY_ID']['BASE']] ?>">
                  <?php echo $CATEGORY[$DB_TABLE['SHOP_CATEGORY_TABLE']['SHOP_CATEGORY_NAME']['BASE']] ?>
                </option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <?php

        $count = 0;

        while ($SHOP = $SQL_SHOP->fetch()) {

          $count++;

          $SQL_ITEM = $con->prepare(
            'SELECT * FROM '
              . $DB_TABLE['ITEM_TABLE']['ITEM_SCHEMA']
              . $DB_TABLE['ITEM_TABLE']['ITEM_TABLE']
              . ' WHERE '
              . $DB_TABLE['ITEM_TABLE']['ITEM_VNUM'][$DB_TYPE] . ' = ?'
          );
          $SQL_ITEM->execute([$SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_VNUM']['BASE']]]);
          $ITEM = $SQL_ITEM->fetch();
        ?>

          <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 position-relative">
            <?php if ($SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_STATS']['BASE']]) { ?>
              <div class="position-absolute shop_item_stats_btn_container">
                <button data-bs-toggle="offcanvas" data-bs-target="<?php echo '#ITEM_OFFCANVAS_' . $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_ID']['BASE']] ?>" aria-controls="<?php echo 'ITEM_OFFCANVAS_' . $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_ID']['BASE']] ?>">
                  <i class="fa-solid fa-circle-info"></i>
                </button>
              </div>
            <?php } ?>
            <div class="item blog-article" data-bs-toggle="modal" data-bs-target="<?php echo '#ITEM_MODAL_' . $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_ID']['BASE']] ?>">
              <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-center align-items-center">
                  <div class="img-shopSquare">
                    <img src="http://westnos.it/images/items/<?php echo $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_VNUM']['BASE']] ?>.png" class="shop_img">
                  </div>
                </div>
                <h4><?php echo substr($ITEM[$DB_TABLE['ITEM_TABLE']['ITEM_NAME']['BASE']], 0, 20) . '...' ?></h4>
                <div><i class="fa fa-dollar text-warning"></i> <?php echo $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_PRICE']['BASE']] ?></div>
              </div>
            </div>
          </div>

          <div class="modal" id="<?php echo 'ITEM_MODAL_' . $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_ID']['BASE']] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <div class="d-flex gap-4 align-items-center">
                    <img src="http://westnos.it/images/items/<?php echo $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_VNUM']['BASE']] ?>.png" style="width: 40px;">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"><?php echo $ITEM[$DB_TABLE['ITEM_TABLE']['ITEM_NAME']['BASE']] ?></h1>
                  </div>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body item-form" method="POST" id="<?php echo 'FORM_' . $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_ID']['BASE']] ?>">
                  <div class="d-flex flex-column gap-4">
                    <p><?php echo $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_DESC']['BASE']] ?></p>
                    <div class="d-flex gap-4 align-items-center">
                      <input type="hidden" name="itemVNum" value="<?php echo $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_VNUM']['BASE']] ?>">
                      <select class="form-control form-control-lg" name="selectCharacter" required>
                        <option value="" disabled selected>Select character</option>
                        <?php

                        $SQL_CHAR = $con->prepare(
                          'SELECT * FROM '
                            . $DB_TABLE['CHARACT_TABLE']['CHARACT_SCHEMA']
                            . $DB_TABLE['CHARACT_TABLE']['CHARACT_TABLE']
                            . ' WHERE '
                            . $DB_TABLE['CHARACT_TABLE']['CHARACT_ACC_ID'][$DB_TYPE] . ' = ?'
                        );
                        $SQL_CHAR->execute([$_SESSION['USER_ID']]);

                        while ($CHAR = $SQL_CHAR->fetch()) { ?>
                          <option value="<?php echo $CHAR[$DB_TABLE['CHARACT_TABLE']['CHARACT_ID']['BASE']] ?>">
                            <?php echo $CHAR[$DB_TABLE['CHARACT_TABLE']['CHARACT_NAME']['BASE']] ?>
                          </option>
                        <?php } ?>
                      </select>
                      <div class="d-flex gap-2 align-items-center h3 m-0">
                        <i class="fa fa-dollar text-warning"></i>
                        <?php echo $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_PRICE']['BASE']] ?>
                      </div>
                    </div>

                    <?php
                    if ($SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_AMOUNT']['BASE']] != 1) {
                      $AMOUNTS = explode("|", $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_AMOUNT']['BASE']]);
                      echo '<div class="input-group">';
                      echo '<span class="input-group-text" id="basic-addon1">Amount</span>';
                      echo '<select class="form-control form-control-lg" name="amount" required>';
                      foreach ($AMOUNTS as $AMOUNT) {
                        echo '<option value="' . $AMOUNT . '">' . $AMOUNT . '</option>';
                      }
                      echo '</select>';
                      echo '</div>';
                    }
                    ?>
                    <button type="submit" class="btn btn-primary btn-lg">
                      Buy
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php if ($SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_STATS']['BASE']]) { ?>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="<?php echo 'ITEM_OFFCANVAS_' . $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_ID']['BASE']] ?>">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title"><?php echo $ITEM[$DB_TABLE['ITEM_TABLE']['ITEM_NAME']['BASE']] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <img src="<?php echo $SHOP[$DB_TABLE['SHOP_TABLE']['SHOP_STATS']['BASE']] ?>" alt="stats" class="w-100">
              </div>
            </div>
          <?php } ?>
        <?php } ?>
        <?php if ($count === 0) { ?>
          <div class="d-flex flex-column gap-4 align-items-center">
            <i class="fa-solid fa-poo h1 text-warning"></i>
            <h4>There are no items in this category</h4>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- ***** Latest News End ***** -->

<script type="text/javascript">
  const buyItemFunction = (e) => {

    e.preventDefault();

    $.ajax({
      type: e.target.method,
      url: 'process?to=buyShopItem',
      data: $(`#${e.target.id}`).serialize(),
      success: res => {
        let obj = JSON.parse(res);
        if (obj.refresh) window.location = 'index'
        else Swal.fire(obj).then(() => window.location = 'shop')
      },
      error: err => console.log(err)

    });
  };

  $(document).ready(function() {

    $("[href='shop']").addClass('active')

    $(".item-form").on("submit", buyItemFunction)

    $("#selectCategory").on("change", e => {

      window.location = `./index?to=shop&category=${e.target.value}`
    })

  })
</script>