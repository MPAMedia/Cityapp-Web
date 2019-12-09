<?php
    $this->load->view("backend/header-no-auth");

?>

      <div class="login-box-body">
        <p class="login-box-msg">

          <div class="msgError alert alert-error alert-dismissible hidden">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-check"></i> <?=Translate::sprint("Error")?>!</h4>
              <div class="msgErrorText"> <?=Translate::sprint("Login_error")?></div>
          </div>

          <div class="msgSuccess alert alert-success alert-dismissible hidden">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-check"></i><?=Translate::sprint("Success","")?> !</h4>
              <?=Translate::sprint("Your account has created","")?>
          </div>
        </p>

        <form id="form" method="post">
            <div class="form-group has-feedback">
                <i class="mdi mdi-account form-control-feedback"></i>
                <input type="text" id="name" class="form-control" placeholder="<?=Translate::sprint("Full name")?>" value="">
            </div>
            <div class="form-group has-feedback">
                <i class="mdi mdi-mail-ru form-control-feedback"></i>
                <input type="email" id="email" class="form-control" placeholder="<?=Translate::sprint("Email","Email")?>" value="">
            </div>
          <div class="form-group has-feedback">
            <i class="mdi mdi-account-key form-control-feedback"></i>
              <input type="text" id="username" class="form-control" placeholder="<?=Translate::sprint("Username","Username")?>" value="">
          </div>
          <div class="form-group has-feedback">
            <i class="mdi mdi-key form-control-feedback"></i>
              <input type="password" id="password" class="form-control" placeholder="<?=Translate::sprint("Password","Password")?>"  value="">
          </div>
            <?php if(ModulesChecker::isRegistred("pack")): ?>
            <!--<div class="form-group">
                <?php

                    $this->load->model("pack/pack_model","mPack");
                    $packs = $this->mPack->getPacks();


                    $selected_pack = intval($this->session->pack_id);
                    if($selected_pack==0){
                        $selected_pack = intval($this->input->get("selected_pack"));
                    }

                ?>
                <label><?=Translate::sprint("Pack")?></label>

                <select class="form-control select2" id="select_pack" data-placeholder="<?=Translate::sprint("Select a pack")?>"
                        style="width: 100%;">
                    <option value="0"><?=Translate::sprint("Select a pack")?></option>
                    <?php foreach ($packs as $value): ?>

                        <?php
                            $selected = "";
                            if($selected_pack==$value->id)
                                $selected ="selected";
                        ?>
                        <?php

                        if($value->price>0)
                            $price = CurrencyUtils::parseFormat($value->price,DEFAULT_CURRENCY);
                        else
                            $price = Translate::sprint("FREE");

                        ?>
                    <option value="<?=$value->id?>" <?=$selected?>><?=$value->name?>, <?=$price?></option>
                    <?php endforeach;?>
                </select>
            </div>-->
            <?php endif; ?>


          <div class="row">
              <div class="col-xs-7">
                  <div class="checkbox icheck">
                      <label>
                          <a href="<?=admin_url("user/login")?>"><?=Translate::sprint("have_already_account","Have already account ?")?></a>
                      </label>
                  </div>
              </div><!-- /.col -->
            <div class="col-xs-5">
                <button type="submit"  class="btn btn-primary btn-block btn-flat signup"><?=Translate::sprint("signup","Sign up")?></button>
            </div><!-- /.col -->
          </div>
        </form>

        <!--<a href="#">I forgot my password</a><br>
        <a href="register.html" class="text-center">Register a new membership</a>-->

      </div><!-- /.login-box-body -->

</div><!-- /.login-box -->


     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?=  base_url("views/skin/backend/bootstrap/js/bootstrap.min.js")?>"></script>
    <!-- iCheck -->
    <script src="<?=  base_url("views/skin/backend/plugins/iCheck/icheck.min.js")?>"></script>
    
    
    <script>
        
        <?php
            $token = $this->mUserBrowser->setToken("S0XsOi");
        ?>
        
                $("#form .signup").on('click',function(){

                    var selector = $(this);
                   
                    var email = $("#form #email").val();
                    var password = $("#form #password").val();
                    var username = $("#form #username").val();
                    var name = $("#form #name").val();

                    
                    $.ajax({
                        url:"<?=  site_url("ajax/user/signUp")?>",
                        data:{
                            "email":email,
                            "password":password,
                            "username":username,
                            "name":name,
                            <?php if(ModulesChecker::isRegistred("pack")): ?>
                           /* "pack_id": $("#select_pack").val(),*/
                            <?php endif; ?>
                            "token":"<?=$token?>"},
                        dataType: 'json',
                        type: 'POST',
                        beforeSend: function (xhr) {
                            $("#form .signup").attr("disabled",true);
                        },error: function (request, status, error) {
                            alert(request.responseText);
                            selector.attr("disabled",false);
                            console.log(request);
                        },
                        success: function (data, textStatus, jqXHR) {


                            selector.attr("disabled",false);

                            if(data.success===1){
                                $(".msgSuccess").removeClass("hidden");

                                if(!data.url)
                                    document.location.href = "<?=admin_url("")?>";
                                else
                                    document.location.href = data.url;

                            }else if(data.success===0){
                                var errorMsg = "";
                                for(var key in data.errors){
                                    errorMsg = errorMsg+" - "+data.errors[key]+"\n";
                                }
                                if(errorMsg!==""){
                                    $(".msgError").removeClass("hidden");
                                    $(".msgErrorText").html(errorMsg);
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
