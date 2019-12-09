<?php
$this->load->view("backend/header-no-auth");
?>

<div class="login-box-body">
    <p class="login-box-msg">


    <div class="msgError alert alert-error alert-dismissible hidden">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> <?= Translate::sprint("Error") ?>!</h4>
        <div class="msgErrorText"> <?= Translate::sprint("Login_error") ?></div>
    </div>

    <div class="msgSuccess alert alert-success alert-dismissible hidden">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> <?= Translate::sprint("Success") ?>!</h4>
        <?= Translate::sprint("Login_seccessfully") ?>
    </div>

    <?php

    $login = "";
    $password = "";

    if (defined("DEMO") and DEMO) {

        $login = admin_login;
        $password = admin_password;

    }

    ?>

    </p>

    <form id="form" method="post">
        <div class="form-group has-feedback">
            <i class="mdi mdi-account form-control-feedback"></i>
            <input type="email" id="email" class="form-control"
                   placeholder="<?= Translate::sprint("Pseudo or Mail", "") ?>" value="<?= $login ?>">
        </div>
        <div class="form-group has-feedback">
            <i class="mdi mdi-key form-control-feedback"></i>
            <input type="password" id="password" class="form-control"
                   placeholder="<?= Translate::sprint("Password", "") ?>" value="<?= $password ?>">
        </div>

        <?php
            $languages =  Translate::getLangsCodes();
            $default_language = Translate::getDefaultLang();
        ?>
        <div class="form-group ">
            <select class="select2" id="default-language">
               <?php foreach ($languages as $key => $lng): ?>
                <option value="<?=$key?>" <?php if($key==$default_language) echo 'selected'?>><?=strtoupper($key).' - '.$lng['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php  if(reCAPTCHA==TRUE): ?>
            <div class="row">
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="6Ld6s4QUAAAAAFmGpn_BkutDOAtbP3ezPnJrzyu1"></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xs-7">
                <div class="checkbox icheck">
                    <label>
                        <a href="<?= site_url("user/fpassword") ?>"><?= Translate::sprint("Forgot_Password", "Forgot password ?") ?></a>
                    </label>
                </div>
            </div><!-- /.col -->
            <div class="col-xs-5">
                <button type="submit"
                        class="btn btn-primary btn-block btn-flat connect"><?= Translate::sprint("Login", "") ?></button>
            </div><!-- /.col -->
        </div>
    </form>


    <!--<a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a>-->

</div><!-- /.login-box-body -->

<?php if (USER_REGISTRATION == TRUE): ?>
    <a style="display: inherit;margin-top: 10px" class="create-account-btn btn btn-primary btn-flat "
       href="<?= site_url("user/signup") ?>">
        <?= Translate::sprint("dont_have_account", "Don't have account ?") ?>
    </a>
<?php endif; ?>

</div><!-- /.login-box -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?= base_url("views/skin/backend/bootstrap/js/bootstrap.min.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/iCheck/icheck.min.js") ?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/select2/select2.full.min.js")?>"></script>


<script>

    <?php

    $token = $this->mUserBrowser->setToken("S69BMNSJB8JB");

    ?>

    $("#form .connect").on('click', function () {


        var email = $("#form #email").val();
        var password = $("#form #password").val();
        var recaptcha_response = $("#form #g-recaptcha-response").val();


        $.ajax({
            url: "<?=  site_url("ajax/user/signIn")?>",
            data: {
                "login": email,
                "password": password,
                "recaptcha_response": recaptcha_response,
                "token": "<?=$token?>"
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                $("#form .connect").attr("disabled", true);

                $(".msgError").addClass("hidden");
                $(".msgSuccess").addClass("hidden");

            }, error: function (request, status, error) {
                alert(request.responseText);
                $("#form .connect").attr("disabled", false);
                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {

                $("#form .connect").attr("disabled", false);

                if (data.success === 1) {
                    $(".msgSuccess").removeClass("hidden");
                    if(data.url === undefined){
                        document.location.href = "<?=admin_url("")?>";
                    }else if(data.url !== undefined && data.url !== ""){
                        document.location.href = data.url;
                    }

                } else if (data.success === 0) {
                    $("#form .connect").attr("disabled", false);
                    var errorMsg = "";
                    for (var key in data.errors) {
                        errorMsg = errorMsg + " - " + data.errors[key] + "\n";
                    }
                    if (errorMsg !== "") {
                        $(".msgError").removeClass("hidden");
                        $(".msgErrorText").html(errorMsg);
                    }
                }
            }
        });

        return false;
    });


    $("#default-language").select2();
    $("#default-language").on('change',function () {

        var code = $(this).val();
        var url = "<?=site_url("user/login")."?lang="?>"+code;
        document.location.href = url;

        return true;
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


<?php if (reCAPTCHA == TRUE): ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
<?php endif; ?>
</body>
</html>
