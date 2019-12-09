<?php

$list = $campaigns[Tags::RESULT];
$pagination = $campaigns["pagination"];

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages");?>
            </div>

        </div>

        <div class="row">

            <div class="col-sm-5">
                <form id="form">
                    <div class="box box-solid">
                        <div class="box-header">

                            <div class="box-title">
                                <b><i class="mdi mdi-bullseye"></i>&nbsp;&nbsp;
                                    <?=Translate::sprint("Create new campaign","")?></b>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <div class="callout callout-info">
                              <p> <?=Translate::sprint("Alert_compaign","")?></p>
                            </div>


                            <div class="form-group">
                                <label><?=Translate::sprint("Type campaign","")?> <sup>*</sup></label>
                                <select class="form-control select2 selectCType" style="width: 100%;">
                                    <option selected="selected" value="0">
                                        <?=Translate::sprint("Select campaign type","")?></option>
                                    <?php

                                        foreach (json_decode(CAMPAIGN_TYPES,JSON_OBJECT_AS_ARRAY) as $value){
                                            echo " <option value=\"$value\">".Translate::sprint(ucfirst($value))."</option>";
                                        }

                                    ?>

                                </select>
                            </div>


                            <div class="form-group drop-box drop-box-store hidden">
                                <label><?=Translate::sprint("Store","")?></label>
                                <select class="form-control select2 selectStore" style="width: 100%;">
                                    <option selected="selected" value="0">
                                        <?=Translate::sprint("Select store","")?></option>
                                    <?php

                                        if(isset($myStores[Tags::RESULT])){
                                            foreach ($myStores[Tags::RESULT] as $st){
                                                echo '<option value="'.$st['id_store'].'">'.$st['name'].'</option>';
                                            }
                                        }

                                    ?>
                                </select>
                            </div>


                            <div class="form-group drop-box drop-box-event hidden">
                                <label><?=Translate::sprint("Event","")?></label>
                                <select class="form-control select2 selectEvent" style="width: 100%;">
                                    <option selected="selected" value="0">
                                        <?=Translate::sprint("Select event","")?></option>
                                    <?php

                                        if(isset($myEvents[Tags::RESULT])){
                                            foreach ($myEvents[Tags::RESULT] as $st){
                                                echo '<option value="'.$st['id_event'].'">'.$st['name'].'</option>';
                                            }
                                        }

                                    ?>
                                </select>
                            </div>


                            <div class="form-group drop-box drop-box-offer hidden">
                                <label><?=Translate::sprint("Offer","")?></label>


                                <select class="form-control select2 selectOffer" style="width: 100%;">
                                    <option selected="selected" value="0">
                                        <?=Translate::sprint("Select offer","")?></option>
                                    <?php

                                        if(isset($myOffers[Tags::RESULT])){
                                            foreach ($myOffers[Tags::RESULT] as $st){
                                                echo '<option value="'.$st['id_offer'].'">'.$st['name']." - ".$this->mStoreModel->getStoreName($st['store_id']).'</option>';
                                            }
                                        }

                                    ?>
                                </select>
                            </div>


                            <div class="form-group box-estimation hidden">
                                <label>
                                    <?=Translate::sprint("Targeting estimation","")?></label>
                                <br>
                                <span> <?=Translate::sprintf("This campaign will be displayed to %s customers within %s KM",array("<span class=\"target_value\">0</span>",RADUIS_TRAGET),"")?> </span>

                            </div>


                            <div class="form-group name hidden">

                                <label> <?=Translate::sprint("Title","")?></label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Ex: campaign_for_black_friday">

<!--
                                <?php if(kdefined("IOS_API") and kdefined("ANDROID_API")): ?>
                                    <br>
                                    <label> <?=Translate::sprint("Specify the platforms")?></label>
                                    <br>
                                    <label><input class="checkplatform" value="android" type="checkbox" checked/>&nbsp;&nbsp;<?=Translate::sprint("Android")?></label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label><input type="checkbox" class="checkplatform" value="ios" checked/>&nbsp;&nbsp;<?=Translate::sprint("iOS")?></label>
                                <?php else: ?>

                                    <?php if(kdefined("IOS_API")): ?>

                                        <br>
                                        <label> <?=Translate::sprint("Specify the platforms")?></label>
                                        <br>
                                        <label><input type="checkbox" class="checkplatform" value="android" disabled/>&nbsp;&nbsp;<?=Translate::sprint("Android")?></label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label><input type="checkbox" class="checkplatform" value="ios" checked/>&nbsp;&nbsp;<?=Translate::sprint("iOS")?></label>

                                    <?php else: ?>


                                        <br>
                                        <label> <?=Translate::sprint("Specify the platforms")?></label>
                                        <br>
                                        <label><input type="checkbox" class="checkplatform" value="android" checked/>&nbsp;&nbsp;<?=Translate::sprint("Android")?></label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label><input type="checkbox" class="checkplatform" value="ios" disabled/>&nbsp;&nbsp;<?=Translate::sprint("iOS")?></label>


                                    <?php endif; ?>

                                <?php endif; ?>

                                -->
                            </div>

                            <br><br>



                            <div class="form-group">
                                <button type="button" class="btn  btn-primary" id="btnCreate" > <span class="fa fa-paper-plane-o"></span>
                                    <?=Translate::sprint("Push","")?> </button>
                            </div>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </form>
                <?php if($this->mUserBrowser->getData("typeAuth")=="admin" and $this->mUserBrowser->getData("manager")==1): ?>
                <form id="test">
                    <div class="box box-solid">
                        <div class="box-header">

                            <div class="box-title">
                                <b><i class="mdi mdi-bullseye"></i>&nbsp;&nbsp;
                                    <?=Translate::sprint("Test campaigns","")?></b>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="callout callout-warning">
                                <p><?=Translate::sprint("To test the campaign in debug mode, you must be sure that at least one shop, event or offer is created")?></p>
                            </div>
                            <div class="form-group">
                                <label><?=Translate::sprint("Type campaign","")?> <sup>*</sup></label>


                                <select class="form-control select2 selectTestCType" style="width: 100%;">
                                    <option selected="selected" value="0">
                                        <?=Translate::sprint("Select campaign type","")?></option>

                                    <?php

                                        foreach (json_decode(CAMPAIGN_TYPES,JSON_OBJECT_AS_ARRAY) as $value){
                                            echo " <option value=\"$value\">".Translate::sprint(ucfirst($value))."</option>";
                                        }

                                    ?>

                                </select>
                            </div>

                            <div class="form-group">
                                <label> <?=Translate::sprint("Guest IDs","")?></label>
                                <input type="text" class="form-control" name="gids" id="gids" placeholder="Ex: 1,2,3...">
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn  btn-primary" id="btnTest" > <span class="fa fa-paper-plane-o"></span>
                                    <?=Translate::sprint("Push","")?> </button>
                            </div>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </form>
                <?php endif; ?>
            </div>



            <?php

                $data['list'] = $list;
                $data['pagination'] = $pagination;
                $this->load->view("backend/html/campaigns",$data);

            ?>


            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->




<!-- page script -->
<script src="<?=  base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/datepicker/bootstrap-datepicker.js")?>"></script>
<script>

    var ctype = "";
    var int_id = 0;
    var t=0;
    $.fn.datepicker.defaults.format = "yyyy-mm-dd";
    $('.datepicker').datepicker({
        startDate: '-3d'
    });


    <?php
        $token = $this->mUserBrowser->setToken("SUSz74aQ55");
        $token2 = $this->mUserBrowser->setToken("SU1Sz74aQ55");
    ?>

    $("#btnCreate").on('click',function(){


        var dataSet0 = {
            "token":"<?=$token?>",
            "name":$("#name").val(),
            "int_id":int_id,
            "t":t,
            "type":ctype
        };

        $.ajax({
            url:"<?=  site_url("ajax/campaign/createCampaign")?>",
            data:dataSet0,
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {

                $("#btnCreate").attr("disabled",true);

            },error: function (request, status, error) {
                alert(request.responseText);
                $("#btnCreate").attr("disabled",false);
                console.log(request)
            },
            success: function (data, textStatus, jqXHR) {

                $("#btnCreate").attr("disabled",false);
                if(data.success===1){
                    document.location.href="<?=admin_url("campaign/campaigns")?>";
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

<script src="<?=  base_url("views/skin/backend/plugins/select2/select2.full.min.js")?>"></script>
<script>




    //select type of campaign
    $('.selectCType').select2();
    $('.selectCType').on('select2:select', function (e) {

        $(".drop-box-store").addClass("hidden");
        $(".drop-box-event").addClass("hidden");
        $(".drop-box-offer").addClass("hidden");

        $(".box-estimation").addClass("hidden");
        $(".form-group.name").addClass("hidden");

        // Do something
        var data = e.params.data;
        var type = data.id;
        ctype = type;

        if(ctype!=0){

            $("div .drop-box").addClass("hidden");
            $(".drop-box-"+ctype).removeClass("hidden");

        }

    });


    //select store
    $('.selectStore').select2();
    $('.selectStore').on('select2:select', function (e) {
        // Do something
        var data = e.params.data;
        var id = data.id;

        if(id>0){
            ctype = "store";
            int_id = id;
            calculateEstimation("store",id);
        }else{
            $(".box-estimation").addClass("hidden");
            $(".form-group.name").addClass("hidden");
        }


    });


    //select event
    $('.selectEvent').select2();
    $('.selectEvent').on('select2:select', function (e) {
        // Do something
        var data = e.params.data;
        var id = data.id;

        if(id>0){
            ctype = "event";
            int_id = id;
            calculateEstimation("event",id);
        }else{
            $(".box-estimation").addClass("hidden");
            $(".form-group.name").addClass("hidden");
        }

    });

    //select store
    $('.selectOffer').select2();
    $('.selectOffer').on('select2:select', function (e) {
        // Do something
        var data = e.params.data;
        var id = data.id;
        var name = data.text;

        $("#name").val(name);

        if(id>0){
            ctype = "offer";
            int_id = id;
            calculateEstimation("offer",id);
        }else{
            $(".box-estimation").addClass("hidden");
            $(".form-group.name").addClass("hidden");
        }
    });


    /*$("input[type=checkbox].checkplatform").on("change",function () {

        alert($(this).val());

        return true;
    });*/


    //calculate estimation
    function  calculateEstimation(type,int_id) {

        $.ajax({
            url:"<?=  site_url("ajax/campaign/getEstimation")?>",
            data:{
                "token":"<?=$token2?>",
                "int_id":int_id,
                "type":type
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {

                $(".box-estimation .target_value").html('&nbsp;<i class="fa fa-refresh fa-spin"></i>&nbsp;');
                $(".box-estimation").removeClass("hidden");


            },error: function (request, status, error) {

                $(".box-estimation").addClass("hidden");
                console.log(request);

            },
            success: function (data, textStatus, jqXHR) {

                $(".box-estimation .target_value").html('nbsp;0nbsp;');


                if(data.success===1){
                    t = data.result;
                    $(".box-estimation .target_value").html('&nbsp;+'+data.result+'&nbsp;');
                    $(".box-estimation").removeClass("hidden");
                }

                $(".form-group.name").removeClass("hidden");

            }
        });


    }
</script>



<?php if($this->mUserBrowser->getData("typeAuth")=="admin"): ?>
<script>

    $('.selectTestCType').select2();

    $(".selectTestCType").on('select2:select', function (e) {
        // Do something
        var data = e.params.data;
        var id = data.id;

        if(id == 0){
            $("#btnTest").attr("disabled",true);
        }else{
            $("#btnTest").attr("disabled",false);
        }

    });

    $("#btnTest").attr("disabled",true);

    $("#btnTest").on('click',function () {

        $.ajax({
            url:"<?=  site_url("ajax/campaign/testPush")?>",
            data:{
                "token":"<?=$token2?>",
                "type":$(".selectTestCType").val(),
                "guest_ids":$("#gids").val()
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {


                $("#btnTest").attr("disabled",true);

            },error: function (request, status, error) {

                $("#btnTest").attr("disabled",false);
                console.log(request);

            },
            success: function (data, textStatus, jqXHR) {

                $("#btnTest").attr("disabled",false);

                if(data.success===1){
                    alert(data.result);
                }

            }
        });
        return true;
    });


</script>
<?php endif; ?>

