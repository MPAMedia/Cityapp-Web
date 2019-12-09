<?php


$image = $dataToEdit['image'];


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

            <div class="col-sm-6">
                <div class="box box-solid">
                    <div class="box-header">
                        <div class="box-title"><b><?=Translate::sprint("Edit Category")?></b></div>
                    </div>

                    <div class="box-body">

                        <div class="form-group required">
                            <label for="name"><?=Translate::sprint("Image")?></label>

                            <label class="msg-error-form image-data"></label>
                            <input type="file" name="addimage" id="fileupload"><br>
                            <div class="clear"></div>
                            <div id="progress" class="hidden">
                                <div class="percent" style="width: 0%"></div>
                            </div>
                            <div class="clear"></div>
                            <div id="image-previews">


                                <?php if(!empty($image)){ ?>


                                        <?php



                                        $item = "item_".$image;
                                        $data = $image;

                                        $imagesData = _openDir($image);


                                        ?>


                                    <?php if(!empty($imagesData)): ?>
                                        <div class="image-uploaded <?=$item?>">
                                            <a id="image-preview">
                                                <img src="<?=$imagesData['200_200']['url']?>" alt="">
                                            </a>

                                            <div class="clear"></div>
                                            <a href="#" data="<?=$data?>" id="delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;<?=Translate::sprint("delete")?></a>
                                        </div>
                                    <?php endif; ?>

                                    <?php } ?>

                            </div>

                        </div>


                        <div class="form-group">
                            <label><?=Translate::sprint("Category name")?> <sup>*</sup> </label>
                            <input class="form-control"  id="addCat" type="text" placeholder="<?=Translate::sprint("Enter")?> ..."
                                   value="<?=$dataToEdit['name']?>"/>
                        </div>
                          
                        <div class="form-group">
                            <label><?=Translate::sprint("اسم الفئة ")?> <sup>*</sup> </label>
                            <input class="form-control"  id="addCatAr" type="text" placeholder="<?=Translate::sprint("أدخل")?> ..."
                                   value="<?=$dataToEdit['name_ar']?>"/>
                        </div>
                          

                        <div class="form-group">
                            <button type="submit" id="btnEdit" class="btn btn-primary btn-flat"><?=Translate::sprint("edit","Edit")?></button>
                        </div>

                    </div>
                </div>


            </div>


            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- jQuery 2.1.4 -->
<script src="<?=  base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js")?>"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.iframe-transport.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.ui.widget.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.fileupload.js")?>"></script>
<!-- page script -->
<script>




    $("#btnEdit").on('click',function(){

        var cat = $("#addCat").val();
        var cat_ar = $("#addCatAr").val();

        $.ajax({
            url:"<?=site_url("ajax/category/editCategory")?>",
            data:{
                'cat':cat,
                'cat_ar':cat_ar,
                'id':"<?=$dataToEdit['id_category']?>",
                "image":fileUploaded
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                $("#btnEdit").attr("disabled",true);

            },error: function (request, status, error) {
                alert(request.responseText);
                $("#btnEdit").attr("disabled",false);
            },
            success: function (data, textStatus, jqXHR) {

                $("#btnEdit").attr("disabled",false);
                if(data.success===1){
                    /*alert(data.message);*/
                   document.location.href="<?=admin_url("category/categories")?>";
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


    Uploader(true);

    <?php

    $token = $this->mUserBrowser->setToken("SUPIMnAGES-4555");

    ?>
    var fileUploaded = {};


    <?php

        if($image!="" && !empty($imagesData)){
            $item = "item_".$image;
            $data = $image;
            echo "fileUploaded[$image]=$image ;";
        }

    ?>

    $(".image-uploaded #delete").on('click',function(){

        var nameDir = $(this).attr("data");
        delete fileUploaded[nameDir];
        console.log(fileUploaded);
        $(".image-uploaded.item_"+nameDir).remove();

        return false;
    });

    function Uploader(singleFile){

        $('#fileupload').fileupload({
            url: "<?=site_url("ajax/uploader/uploadImage")?>",
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


                console.log(data);

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