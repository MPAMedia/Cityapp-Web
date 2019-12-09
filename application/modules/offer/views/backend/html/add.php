<?php

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


                <div class="col-sm-12">
                    <form id="form">
                        <div class="box box-solid">
                        <div class="box-header">

                            <div class="box-title">
                                <b><?=Translate::sprint("Add new offer","")?></b>
                            </div>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">


                            <div class="col-sm-4">

                                <div class="form-group">
                                    <label><?=Translate::sprint("Store")?></label>
                                    <select class="form-control select2 selectStore" style="width: 100%;">
                                        <option selected="selected" value="0">
                                            <?=Translate::sprint("Select store","")?></option>
                                        <?php

                                        if(isset($myStores[Tags::RESULT])){
                                            foreach ($myStores[Tags::RESULT] as $st){
                                                echo '<option adr="'.$st['address'].'" 
                                        lat="'.$st['latitude'].'" lng="'.$st['longitude'].'" 
                                        value="'.$st['id_store'].'">'.$st['name'].'</option>';
                                            }
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><?=Translate::sprint("Name","")?></label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Ex: black friday">
                                </div><div class="form-group">
                                    <label><?=Translate::sprint("الاسم","")?></label>
                                    <input type="text" class="form-control" name="name_ar" id="name_ar" placeholder="مثال : الجمعة السوداء">
                                </div>

                            </div>


                            <div class="col-sm-4">

                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-sm-6 no-margin">
                                            <?php
                                            $currency = $this->mOfferModel->getDefaultCurrency();

                                            ?>
                                            <label><?=Translate::sprint("Offer price")?></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                  <input name="poffer" type="radio" id="price">
                                                </span>
                                                <input type="number" class="form-control" id="priceInput" placeholder="<?=Translate::sprint("Enter price of your offer")?>">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 no-margin" style="padding-left: 0px;">
                                            <?php

                                                $currencies =  $currencies = json_decode(CURRENCIES,JSON_OBJECT_AS_ARRAY);
                                                $def_currency = $this->mOfferModel->getDefaultCurrency();

                                            ?>
                                            <div class="form-group">
                                                <label><?=Translate::sprint("Select offer currency")?></label>
                                                <select id="selectCurrency" class="form-control select2 selectCurrency" style="width: 100%;">
                                                    <option selected="selected" value="0"> <?=Translate::sprint("Select")?></option>
                                                    <?php

                                                    foreach ($currencies as $key => $value){
                                                        if($key==$def_currency['code'])
                                                            echo '<option selected="selected" value="'.$key.'">'.$value['name'].' ('.$value['code'].')</option>';
                                                        else
                                                            echo '<option value="'.$key.'">'.$value['name'].' ('.$value['code'].')</option>';

                                                    }
                                                    ?>
                                                </select>
                                            </div>


                                        </div>
                                    </div>



                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label><?=Translate::sprint("Offer percent","")?> </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                        <input name="poffer"  type="radio"  id="percent">
                                                </span>
                                                <input type="number" class="form-control"  id="percentInput"  placeholder="Exemple : -50 %">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label> <?=Translate::sprint("Date Begin","")?> </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="mdi mdi-calendar"></i>
                                                        </div>
                                                        <input  class="form-control" data-provide="datepicker" placeholder="YYYY-MM-DD" type="text" name="date_b" id="date_b" value="<?=date("Y-m-d",time())?>"/>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">  <label>
                                                        <?=Translate::sprint("Date End")?> </label>

                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="mdi mdi-calendar"></i>
                                                        </div>
                                                        <input class="form-control"   data-provide="datepicker"  type="text" placeholder="YYYY-MM-DD" name="date_e" id="date_e"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-sm-4">
                                <div class="form-group required">

                                <label for="name"><i class="mdi mdi-paperclip"></i>&nbsp;&nbsp;<?=Translate::sprint("Image","")?> <sup>*</sup></label>
                                <label class="msg-error-form image-data"></label>
                                <input type="file" name="addimage" id="fileupload"><br>
                                <div class="clear"></div>
                                <div id="progress" class="hidden">
                                    <div class="percent" style="width: 0%"></div>
                                </div>
                                <div class="clear"></div>


                                <div id="image-previews">
                                </div>

                            </div>
                            </div>

                            <div class="col-sm-10">
                                <div class="form-group">
                                    <label><?=Translate::sprint("Description","")?></label>
                                    <textarea class="form-control" rows="7"  id="editable-textarea"
                                              placeholder="<?=Translate::sprint("Enter")?> ..."></textarea>
                                </div>
                                 <div class="form-group">
                                    <label><?=Translate::sprint("الوصف","")?></label>
                                    <textarea class="form-control" rows="7"  id="editable-textarea_ar"
                                              placeholder="<?=Translate::sprint("أدخل")?> ..."></textarea>
                                </div>
                      
<!--                                <select class="form-control" id="tags"  multiple="multiple" placeholder>-->
<!--                                </select>-->

                                <br>  <br>
                            </div>

                            <div class="form-group col-sm-12">
                                <div class="form-group">
                                    <button type="button" class="btn  btn-primary" id="btnCreate" > <span class="glyphicon glyphicon-check"></span>
                                        <?=Translate::sprint("Create","")?> </button>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                    </form>
                </div>



            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->



<!-- DataTables -->
<script src="<?=  base_url("views/skin/backend/plugins/datatables/jquery.dataTables.min.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/datatables/dataTables.bootstrap.min.js")?>"></script>
<!-- SlimScroll -->
<script src="<?=  base_url("views/skin/backend/plugins/slimScroll/jquery.slimscroll.min.js")?>"></script>
<!-- FastClick -->
<script src="<?=  base_url("views/skin/backend/plugins/fastclick/fastclick.js")?>"></script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.iframe-transport.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.ui.widget.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.fileupload.js")?>"></script>

<script>

    <?php

    $token = $this->mUserBrowser->setToken("SUPIMAGES-4555");

    ?>

    Uploader(true);

    var fileUploaded = {};
    function Uploader(singleFile){

        $('#fileupload').fileupload({
            url: "<?=site_url("uploader/ajax/uploadImage")?>",
            sequentialUploads: true,
            loadImageFileTypes:/^image\/(gif|jpeg|png|jpg)$/,
            loadImageMaxFileSize: 10000,
            singleFileUploads: singleFile,

            formData     : {
                'token'     : "<?=$token?>",
                'ID'        : "<?=sha1($token)?>"
            },
            dataType: 'json',
            done: function (e, data) {


                var results = data._response.result.results;
                $("#progress").addClass("hidden");
                $("#progress .percent").animate({"width":"0%"});
                $(".image-uploaded").removeClass("hidden");

                if(singleFile==true){
                    fileUploaded = {};
                    $("#image-previews").html(results.html);
                }else
                    $("#image-previews").append(results.html);

                fileUploaded[results.image] = results.image;
                //$("#image-data").val(results.image_data);

                $(".image-uploaded #delete").on('click',function(){
                    var nameDir = $(this).attr("data");
                    delete fileUploaded[nameDir];
                    $(".image-uploaded.item_"+nameDir).remove();
                    return false;
                });

            },
            fail:function (e, data) {

                $("#progress").addClass("hidden");
                $("#progress .percent").animate({"width":"0%"});


            },
            progressall: function (e, data) {

                var progress = parseInt(data.loaded / data.total * 100, 10);

                $("#progress").removeClass("hidden");
                $("#progress .percent").animate({"width":progress+"%"},"linear");

            },
            progress: function (e, data) {

                var progress = parseInt(data.loaded / data.total * 100, 10);

            },
            start: function (e) {

                $("#fileupload").removeClass("input-error");
                $(".image-data").text("");

            }
        });



    }


</script>

<!-- page script -->
<script src="<?=  base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/datepicker/bootstrap-datepicker.js")?>"></script>
<script>


    var store_id = 0;
    $.fn.datepicker.defaults.format = "yyyy-mm-dd";
    $('.datepicker').datepicker({
        startDate: '-3d'
    });


    <?php
    $token = $this->mUserBrowser->setToken("SU74aQ55");
    ?>

    $("#btnCreate").on('click',function(){

        var selector = $(this);

        var description = $("#form #editable-textarea").val();
        var description_ar = $("#form #editable-textarea_ar").val();

        var price = 0;
        var percent = 0;

        if($("#form #price").is(':checked') && $("#form #priceInput").val().length!=0)
            price = $("#form #priceInput").val();

        if($("#form #percent").is(':checked') && $("#form #percentInput").val().length!=0)
            percent = $("#form #percentInput").val();

        var date_b = $("#form #date_b").val();
        var date_e = $("#form #date_e").val();
        var name = $("#form #name").val();
        var name_ar = $("#form #name_ar").val();

        var currency = $("#form #selectCurrency").val();

        var dataSet0 = {
            "token":"<?=$token?>",
            "store_id":store_id,
            "image":fileUploaded,
            "name":name,
            "name_ar":name_ar,
            "description":description,
            "description_ar":description_ar,
            "price":price,
            "percent":percent,
            "date_start":date_b,
            "date_end":date_e,
            "currency":currency
        };


//        console.log(dataSet0);
//
//        return true;

        $.ajax({
            url:"<?=  site_url("ajax/offer/add")?>",
            data:dataSet0,
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {

                selector.attr("disabled",true);

            },error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled",false);
                console.log(request)
            },
            success: function (data, textStatus, jqXHR) {

                selector.attr("disabled",false);
                if(data.success===1){
                    document.location.href="<?=admin_url("offer/offers")?>";
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


    $('.selectCurrency').select2();


    $('.selectStore').select2();
    $('.selectStore').on('select2:select', function (e) {
        // Do something
        var data = e.params.data;
        var id = data.id;
        store_id = id;
        if(id>0){
            store_id = id;
        }else {
            store_id = 0;
        }

    });


    $("#price").attr("checked",true);
    $("#percent").attr("checked",false);
    $("#percentInput").attr("disabled",true);

    $("input[name=poffer]").on('change',function () {

        var checked  = $(this).attr("id");
        if(checked=="price"){
            $("#"+checked+"Input").attr("disabled",false);
            $("#percentInput").attr("disabled",true);
            $("#selectCurrency").attr("disabled",false);
        }else {
            $("#"+checked+"Input").attr("disabled",false);
            $("#priceInput").attr("disabled",true);
            $("#selectCurrency").attr("disabled",true);
        }

    });

//    $("#tags").select2({
//        tags: true,
//        placeholder: "<?//=Translate::sprint("Add tags")?>//",
//        tokenSeparators: [',', ' ']
//    })

</script>



