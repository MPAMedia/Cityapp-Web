<?php

        $users = $data[Tags::RESULT];
        $pagination = $data[Tags::PAGINATION];

        $typeAuth =$this->mUserBrowser->getData("typeAuth");
        
         // $pagination = $data['pagination'];
        
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages");?>
            </div>

        </div>

        <div class="row">
        <div class="col-xs-12">
          <div class="box box-solid">
            <div class="box-header">

                <div class="box-title" style="width : 100%;">

                    <div class="row ">

                        <div class="pull-left col-md-8">
                            <B><?=Translate::sprint("Customers")?></B>
                        </div>

                        <div class="pull-right col-md-4">


                            <?php  if($this->mUserBrowser->getData("typeAuth") !="guest") { ?>
                                <a href="<?=  admin_url("user/add")?>"><button type="button" data-toggle="tooltip" title="<?=Translate::sprint("Create new")?>" class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-plus"></span></button></a>
                            <?php  }  ?>

                            <form method="get" action="<?= admin_url("user/users")?>">

                                <div class="input-group input-group-sm">
                                    <input class="form-control" size="30"  name="search" placeholder="<?=Translate::sprint("Search")?>" type="text" value="<?=htmlspecialchars($this->input->get("search"))?>">
                                    <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-flat"><i class="mdi mdi-magnify"></i></button>
                                </span>
                                </div>

                            </form>

                        </div>


                    </div>


                </div>
             

            </div>
            <!-- /.box-header -->
            <div class="box-body  table-responsive">
                
             
                <div class="table-responsive">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                 <!-- <th>ID</th>-->
                  <th><?=Translate::sprint("Photo","")?></th>
                  <th><?=Translate::sprint("Name","")?></th>
                  <th><?=Translate::sprint("Login","")?></th>
                  <th><?=Translate::sprint("Email","")?></th>
                  <th><?=Translate::sprint("Last visited","")?></th>
                  <th><?=Translate::sprint("Status","")?></th>
                  <th><?=Translate::sprint("Access Role","")?></th>
                  <th> </th>
                </tr>
                </thead>
                <tbody>
              
           <?php if(!empty($users)){ ?>

                        <?php foreach($users AS $user){ ?>
                    <tr>


                        <td>

                            <?php

                                $image = "";

                                if(isset($user['images'][0]['200_200']['url'])){
                                    $image = $user['images'][0]['200_200']['url'];
                                }else{
                                    $image= base_url("views/skin/backend/images/profile_placeholder.png");
                                }


                            ?>

                            <div class="image-container-40"  style="background-image: url('<?=$image?>');background-size: auto 100%;">
                                <img class="direct-chat-img invisible" src="<?=$image?>" alt="Message User Image" >
                            </div>

                        </td>
                        <td>
                            <span style="font-size: 13px" id="trigger"><?=  htmlspecialchars($user['name'])?></span>
                        </td>
                        <td>         
                            <span style="font-size: 13px"><?=  htmlspecialchars($user['username'])?></span>
                        </td>
                         <td>         
                            <span style="font-size: 13px"><?=  htmlspecialchars($user['email'])?></span>
                            
                         </td>
                         <td>

                             <?php

                             $guest = $this->mUserModel->getGuestData($user["guest_id"]);
                             if(!empty($guest)){
                                 echo '<span style="font-size: 13px">'.$guest["last_activity"].'</span><br>';
                                 if($guest["platform"]=="android"){
                                     echo '<span class="badge bg-green" style="font-size: 13px">'.$guest["platform"].'</span>';
                                 }else if($guest["platform"]=="ios"){
                                     echo '<span class="badge bg-blue" style="font-size: 13px">'.$guest["platform"].'</span>';
                                 }
                             }else{
                                 echo '<span style="font-size: 13px">'.htmlspecialchars($user['dateLogin']).'</span>';
                             }

                             ?>


                         </td>
                        <td>
                            <?php

                                if($user['confirmed'] == 0) {
                                    echo ' <span class="badge bg-yellow">'.Translate::sprint("No-Confirmed","").'</span>';
                                } else {
                                    echo ' <span class="badge bg-green">'.Translate::sprint("Confirmed","").'</span>';
                                }

                            ?>
                        </td>
                          <td>


                              <?php

                                  if($user['typeAuth'] == "manager") {
                                      echo '<span class="badge bg-blue">'.Translate::sprint("Owner","").'</span>';
                                  } else if ($user['typeAuth'] == "admin") {
                                      echo '<span class="badge bg-green">'.Translate::sprint("Admin","").'</span>';
                                  }else if ($user['typeAuth'] == "customer") {
                                      echo '<span class="badge bg-yellow">'.Translate::sprint("Customer","").'</span>';
                                  }

                              ?>

                          </td>

                        
                         <td align="right">
                             <?php
                                 if($user['status'] >= 0) {
                                     echo ' <a href="'.site_url("ajax/user/access?id=".$user['id_user']).'"  class="linkAccess" onclick="return false;"><button type="button" data-toggle="tooltip"  title="'.Translate::sprint("Enabled").'" class="btn btn-sm" onclick="return false;"><i style="color:#29910d  !important" class="fa fa-check" aria-hidden="true"></i></button></a>&nbsp;&nbsp;';
                                 }
                                 else if ($user['status'] == -1) {
                                     echo ' <a href="'.site_url("ajax/user/access?id=".$user['id_user']).'"  class="linkAccess" onclick="return false;"><button type="button"  data-toggle="tooltip"  title="'.Translate::sprint("Disabled").'" class="btn btn-sm"  onclick="return false;"><i style="color:#c60f0f !important" class="fa fa-times" aria-hidden="true"></i></button></a>&nbsp;&nbsp;';
                                 }
                             ?>
                             <a href="<?=  admin_url("user/edit?id=".$user['id_user'])?>" ><button type="button" data-toggle="tooltip" title="<?=Translate::sprint("Update profile")?>" class="btn btn-sm"><span class="glyphicon glyphicon-edit"></span></button></a>
                             <a  data="<?=$user['id_user']?>" href="#" class="deleteUser"><button type="button" data-toggle="tooltip" title="<?=Translate::sprint("Delete user")?>" class="btn btn-sm"><span class="glyphicon glyphicon-trash"></span></button></a>
                             <a href="<?=  admin_url("messenger/messages?username=".$user['username'])?>" ><button type="button"  data-toggle="tooltip"  title="<?=Translate::sprint("Inbox")?>" class="btn btn-sm"><span class="glyphicon glyphicon-inbox"></span> </button></a>
                             <?php if($this->mUserBrowser->getData("typeAuth")=="admin"): ?>
                             <a href="<?=  admin_url("user/shadowing?id=".$user['id_user'])?>" ><button  data-toggle="tooltip"  title="<?=Translate::sprint("Shadowing")?>" type="button" class="btn btn-sm"><span class="	glyphicon glyphicon-eye-open"></span> </button></a>
                            <?php endif; ?>


                         </td>
                    </tr>    
                        <?php } ?>
                        <?php }else{ ?>
                      <tr>
                          <td colspan="7" align="center"><div style="text-align: center"> <?=Translate::sprint("No data found")?></div></td>
                      </tr>
                      
                        <?php } ?>
                </tbody>
               <!-- <tfoot>
                <tr>
                  <th>Rendering engine</th>
                  <th>Browser</th>
                  <th>Platform(s)</th>
                  <th>Engine version</th>
                  <th>CSS grade</th>
                </tr>
                </tfoot>-->
              </table>
                </div>
                    
                   <div class="row">
                          <div class="col-sm-12">
                              <div class="dataTables_info  pull-right" id="example2_info" role="status" aria-live="polite">
                                  <?php

                                    echo $pagination->links(array(
                                        "search"    => $this->input->get("search")
                                    ),admin_url("user/users"));

                                  ?>
                              </div>
                                  
                          </div>
                         
                      </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div style="text-align: center">
                        <h3 class="text-red"><?=Translate::sprint("Are you sure?")?></h3>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="_apply"  class="btn btn-flat btn-primary pull-right"><?=Translate::sprint("Yes")?></button>
                <button type="button" class="btn btn-flat btn-default pull-right" data-dismiss="modal"><?=Translate::sprint("No")?></button>
            </div>
        </div>

        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="switcher">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                <div class="callout callout-info">
                    <p> <?=Translate::sprint("You should know that you can sign all stores, events and offers to another owner by selecting the owner from the list above")?></p>
                </div>

                <div class="form-group">
                    <label><?=Translate::sprint("Select owner")?></label>
                    <select id="select_owner" name="select_owner" class="form-control select2">
                        <option selected="" value="0">---- <?=Translate::sprint("Select")?></option>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?=Translate::sprint("Cancel","Cancel")?></button>
                <button type="button" id="apply" class="btn btn-flat btn-primary"><?=Translate::sprint("Apply and delete")?></button>
            </div>
        </div>

        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- jQuery 2.1.4 -->
<script src="<?=  base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js")?>"></script>
<!-- page script -->
<script>
    
    $('a.linkAccess').on('click',function(){

        //$('#myModal').modal('show');
        //('#myModal').modal('hide');

        var url = ($(this).attr('href'));
        $('#modal-default').modal('show');

        $("#_apply").on('click',function () {
            //calling the ajax function
            pop(url);
            return true;
        });
      });


function getURLParameter(url, name) {
    return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
}

function pop(url) {

    $.ajax({
       type:'post',
       url:url,
       dataType: 'json',
       beforeSend: function (xhr) {
                            $(".linkAccess").attr("disabled",true);
                        },error: function (request, status, error) {
                            alert(request.responseText);
                            $(".linkAccess").attr("disabled",false);
                            $('#switcher').modal('hide');
                            $('#modal-default').modal('hide');
                        },
                        success: function (data, textStatus, jqXHR) {

                            $('#switcher').modal('hide');
                            $('#modal-default').modal('hide');
                            
                            $(".linkAccess").attr("disabled",false);
                            if(data.success===1){
                                document.location.reload()
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
          }

</script>

<script src="<?=  base_url("views/skin/backend/plugins/select2/select2.full.min.js")?>"></script>
<?php if($this->mUserBrowser->getData("typeAuth")=="admin"): ?>
    <script>


        $('#select_owner').select2({

            ajax: {
                url: "<?=site_url("ajax/user/getOwners")?>",
                dataType:"json",
                data: function (params) {

                    var query = {
                        q: params.term,
                    };

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data) {
                    // Tranforms the top-level key of the response object from 'items' to 'results'
                    console.log(data);
                    return {
                        results: data
                    };
                },
                results: function (data, page) {
                    console.log(data);

                    return { results: data };
                }
            }
        });


        $("div .deleteUser").on('click',function () {

            var user_id = parseInt($(this).attr("data"));

            $('#switcher').modal('show');

            $("#apply").on('click',function () {

                var switch_to = parseInt($("#select_owner").val());

                applyDelete(user_id,switch_to);

                return false;
            });

            return false;
        });


        function applyDelete(user_id,switch_to) {

            $.ajax({
                type:'post',
                url:"<?=site_url("ajax/user/delete")?>",
                data:{
                    "id": user_id,
                    "switch_to": switch_to,
                },
                type: "POST",
                dataType: 'json',
                beforeSend: function (xhr) {
                    $("#apply").attr("disabled",true);
                },error: function (request, status, error) {

                    $("#apply").attr("disabled",false);
                    $('#switcher').modal('hide');
                    $('#modal-default').modal('hide');

                    console.log(request);
                },
                success: function (data, textStatus, jqXHR) {

                    console.log(data);

                    $('#switcher').modal('hide');
                    $('#modal-default').modal('hide');

                    $("#apply").attr("disabled",false);

                    if(data.success===1){
                        document.location.reload()
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

        }


    </script>
<?php endif;?>




