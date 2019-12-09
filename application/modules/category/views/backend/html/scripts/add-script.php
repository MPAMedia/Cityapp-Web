<!-- page script -->
<script>

    $("#btnAdd").on('click',function(){

        var name = $("#name").val();

        $.ajax({
            url:"<?=site_url("ajax/category/addCategory")?>",
            data:{
                'cat':name,
                "image":<?=$uploader_variable?>
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