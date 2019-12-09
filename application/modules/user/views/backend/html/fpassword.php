<?php
  $this->load->view("backend/header-no-auth");
?>

      <div class="login-box-body">
        <p class="login-box-msg"><?=Translate::sprint("Forgot_Password","")?></p>
        <form id="form" method="post">

          <div class="msgSuccess alert alert-success alert-dismissible hidden">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> <?=Translate::sprint("Success","")?> !</h4>

              <?=Translate::sprint("We've sent new password to your mailbox","")?>
          </div>

          <div class="form-group has-feedback">
              <input type="text" id="login" class="form-control" placeholder="<?=Translate::sprint("Enter your email","")?>">
            <i class="mdi mdi-mail-ru form-control-feedback"></i>
          </div>
          <div class="row">
            <div class="col-xs-7">
              <div class="checkbox icheck">
                <label>
                  <a href="<?=site_url("user/login")?>"><?=Translate::sprint("Login","Log In")?></a>
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-5">
              <button type="submit"  class="btn btn-primary btn-block btn-flat connect">
                  <?=Translate::sprint("Send password","")?></button>
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
            $token = $this->mUserBrowser->setToken("S69BMNSJB8JB");
        ?>
        
                $("#form .connect").on('click',function(){

                    var login = $("#form #login").val();

                    $.ajax({
                        url:"<?=  site_url("ajax/user/forgetpassword")?>",
                        data:{"login":login,"token":"<?=$token?>"},
                        dataType: 'json',
                        type: 'POST',
                        beforeSend: function (xhr) {
                            $("#form .connect").attr("disabled",true);
                        },error: function (request, status, error) {
                            alert(request.responseText);
                            $("#form .connect").attr("disabled",false);
                            console.log(request);
                        },
                        success: function (data, textStatus, jqXHR) {

                          console.log(data);
                        
                            $("#form .connect").attr("disabled",false);
                            if(data.success===1){

                                $(".msgSuccess").removeClass("hidden");


                                <?php

                                    if($this->session->agent=="mobile"){
                                       // echo ' document.location.href = ""';
                                    }

                                ?>

                            }else if(data.success===0){
                                var errorMsg = "";
                                for(var key in data.errors){
                                    errorMsg = errorMsg+data.errors[key]+"\n";
                                }
                                if(errorMsg!==""){
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
