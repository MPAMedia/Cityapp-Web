<?php
$token = $this->mUserBrowser->setToken("SUSz74aQ55");
$token2 = $this->mUserBrowser->setToken("SU1Sz74aQ55");
?>
<!-- page script -->
<script src="<?=  base_url("views/skin/backend/plugins/datepicker/bootstrap-datepicker.js")?>"></script>
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
<script>

    var ctype = "";
    var int_id = 0;
    var t=0;
    $.fn.datepicker.defaults.format = "yyyy-mm-dd";
    $('.datepicker').datepicker({
        startDate: '-3d'
    });


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
<?php if(GroupAccess::isGranted('user',USER_ADMIN)): ?>
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