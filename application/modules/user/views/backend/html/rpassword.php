<?php
$this->load->view("backend/header-no-auth");
?>
<div class="login-box-body">
    <p class="login-box-msg"><?= Translate::sprint("Connect", "") ?></p>
    <form id="form" method="post">

        <div class="form-group has-feedback">
            <input type="password" id="password" class="form-control" placeholder="New password ">
            <i class="mdi mdi-key  form-control-feedback"></i>
        </div>
        <div class="form-group has-feedback">
            <input type="password" id="confirm" class="form-control" placeholder="Confirm">
            <i class="mdi mdi-check form-control-feedback"></i>
        </div>
        <div class="row">
            <div class="col-xs-7">
                <div class="checkbox icheck">
                    <!--<label>
                      <input type="checkbox"> Remember Me
                    </label>-->
                </div>
            </div><!-- /.col -->
            <div class="col-xs-5">
                <button type="submit"
                        class="btn btn-primary btn-block btn-flat resetP"><?= Translate::sprint("Reset", "") ?></button>
            </div><!-- /.col -->
        </div>
    </form>


    <!--<a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a>-->

</div><!-- /.login-box-body -->
</div><!-- /.login-box -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="<?= base_url("views/skin/backend/bootstrap/js/bootstrap.min.js") ?>"></script>
<!-- iCheck -->
<script src="<?= base_url("views/skin/backend/plugins/iCheck/icheck.min.js") ?>"></script>


<script>

    <?php


    $stoken = $this->input->get("recover");
    if (Text::tokenIsValid($stoken)) {
    } else
        $stoken = "WTF!";

    $token = $this->mUserBrowser->setToken("S69BMNSJB8JB");

    ?>

    $("#form .resetP").on('click', function () {


        var password = $("#form #password").val();
        var confirm = $("#form #confirm").val();


        $.ajax({
            url: "<?=  site_url("ajax/user/resetpassword")?>",
            data: {
                "password": password,
                "confirm": confirm,
                "token": "<?=$token?>",
                "stoken": "<?=$stoken?>"
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                $("#form .resetP").attr("disabled", true);
            }, error: function (request, status, error) {
                alert(request.responseText);
                $("#form .resetP").attr("disabled", false);
                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {

                console.log(data);

                $("#form .resetP").attr("disabled", false);
                if (data.success === 1) {
                    document.location.href = "<?=site_url("user/login")?>";
                } else if (data.success === 0) {
                    var errorMsg = "";
                    for (var key in data.errors) {
                        errorMsg = errorMsg + data.errors[key] + "\n";
                    }
                    if (errorMsg !== "") {
                        alert(errorMsg);
                    }
                }
            }
        });

        return false;
    });


</script>


<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
