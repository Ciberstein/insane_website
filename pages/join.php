<!-- ***** Banner Start ***** -->
<div class="main-banner_3">
  <div class="row">
    <div class="col-lg-7">
      <div class="header-text">
        <h6>Join this great community</h6>
        <h4><em><?php echo $site['name'] ?></em> Join</h4>
      </div>
    </div>
  </div>
</div>
<!-- ***** Banner End ***** -->

<!-- ***** Join Start ***** -->
<div class="most-popular text-light">
  <div class="row">
    <div class="col-lg-6">
      <div class="p-4">
        <div class="heading-section">
          <h4><em>Log</em> in</h4>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <form id="LoginForm" method="POST">
              <div class="mb-3">
                <label for="inputLoginUsername" class="form-label">Username</label>
                <input type="text" class="form-control form-control-lg" id="inputLoginUsername" name="inputLoginUsername" required>
              </div>
              <div class="mb-3">
                <label for="inputLoginPassword" class="form-label">Password</label>
                <input type="password" class="form-control form-control-lg" id="inputLoginPassword" name="inputLoginPassword" required>
              </div>
              <button type="submit" class="btn btn-primary btn-lg">Log in</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="p-4">
        <div class="heading-section">
          <h4><em>Sign</em> in</h4>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <form id="RegisterForm" method="POST">
              <div class="row">
                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="inputRegisterUsername" class="form-label">Username</label>
                    <input type="text" class="form-control form-control-lg" id="inputRegisterUsername" name="inputRegisterUsername" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="inputRegisterEmail" class="form-label">Email</label>
                    <input type="email" class="form-control form-control-lg" id="inputRegisterEmail" name="inputRegisterEmail" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="inputRegisterPassword" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-lg" id="inputRegisterPassword" name="inputRegisterPassword" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="inputRegisterPasswordAgain" class="form-label">Repeat Password</label>
                    <input type="password" class="form-control form-control-lg" id="inputRegisterPasswordAgain" name="inputRegisterPasswordAgain" required>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-lg">Register</button>
            </form>
          </div>
        </div>
        <div>
        </div>
      </div>
    </div>
    <!-- ***** Join End ***** -->

    <script type="text/javascript">
      $(document).ready(() => {

        const JoinFunction = formId => {

          $.ajax({

            type: $(`#${formId}`).attr("method"),
            url: 'process?to=JoinSystem',
            data: $(`#${formId}`).serialize(),
            success: res => {
              let obj = JSON.parse(res);
              if (obj.refresh) window.location = 'index'
              else Swal.fire(obj)
            },
            error: err => console.log(err)

          });

        }


        $('#LoginForm').on('submit', e => {

          e.preventDefault()
          JoinFunction('LoginForm')

        })


        $('#RegisterForm').on('submit', e => {

          e.preventDefault()
          JoinFunction('RegisterForm')

        })

      });

      $("[href='join']").addClass('active')
    </script>