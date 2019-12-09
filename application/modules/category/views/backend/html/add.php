
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
                        <div class="box-title"><b><?=Translate::sprint("Add new category")?></b></div>
                    </div>

                    <div class="box-body">

                        <div class="form-group required">
                            <label for="name"><?=Translate::sprint("Image")?> <sup>*</sup> <span style="color: grey;font-size: 11px">(100x350)</span></label>

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


                        <div class="form-group">
                            <label><?=Translate::sprint("Category name")?> <sup>*</sup> </label>
                            <input class="form-control"  id="name" type="text" placeholder="<?=Translate::sprint("Enter")?> ..."/>
                        </div>
                        <div class="form-group">
                            <label><?=Translate::sprint("اسم الفئة ")?> <sup>*</sup> </label>
                            <input class="form-control"  id="cat_ar" type="text" placeholder="<?=Translate::sprint("أدخل ")?> ..."/>
                        </div>
                      

                        <div class="form-group">
                            <button type="submit" id="btnAdd" class="btn btn-primary btn-flat"><?=Translate::sprint("Add")?></button>
                        </div>

                    </div>
                </div>


            </div>

            <?php $this->load->view("backend/html/list");?>


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


    $("#btnAdd").on('click',function(){

        var name = $("#name").val();
        var cat_ar = $("#cat_ar").val();
        $.ajax({
            url:"<?=site_url("ajax/category/addCategory")?>",
            data:{
                'cat':name,
                'cat_ar':cat_ar,
                "image":fileUploaded
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                $("#btnAdd").attr("disabled",true);

            },error: function (request, status, error) {
                alert(request.responseText);
                $("#btnAdd").attr("disabled",false);
            },
            success: function (data, textStatus, jqXHR) {


                $("#btnAdd").attr("disabled",false);
                if(data.success===1){
                    /*alert(data.message);*/
                    location.reload();
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

                console.log(data);

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