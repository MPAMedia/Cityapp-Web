<?php

$tag_id = "";

if (isset($tag)) {
    $tag_id = "-" . $tag;
}

?>

<?php if(Uploader::$nbr_request==1): ?>
    <script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.iframe-transport.js") ?>"></script>
    <script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.ui.widget.js") ?>"></script>
    <script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.fileupload.js") ?>"></script>
    <script src="<?= base_url("views/skin/backend/plugins/jQueryUI/jquery-ui.js") ?>"></script>
<?php endif; ?>
<script>

    <?php

    $token = $this->mUserBrowser->setToken($token_key);

    ?>
    var <?=$array_name?> = {};

    <?php if (isset($cache) and !empty($cache)): ?>

    $("#image-previews<?=$tag_id?> #delete").on('click', function () {

        var nameDir = $(this).attr("data");
        $("#image-previews<?=$tag_id?> .image-uploaded.item_" + nameDir).fadeOut('linear');

        refreshGallery<?=$rand?>();
        deleteItem<?=$rand?>(nameDir);

        return false;
    });

    <?php endif; ?>


    <?php
    if (isset($cache) and !empty($cache)) {
        foreach ($cache as $key => $value) {

            if(!isset($value['name']))
                continue;

            $name = $value['name'];
            $item = "item_" . $name;
            echo $array_name."[" . $name . "]=$name ;";
        }
    }
    ?>

    refreshGallery<?=$rand?>();

    $('#fileuploadbtn<?= $tag_id ?>').on('click', function() {
        $('#fileuploadinput<?= $tag_id ?>').trigger('click');
    });

    $('#fileuploadinput<?= $tag_id ?>').fileupload({
        url: "<?=site_url("uploader/ajax/uploadImage")?>",
        sequentialUploads: true,
        limitMultiFileUploadSize: 6,
        loadImageFileTypes: /^image\/(gif|jpeg|png|jpg)$/,
        loadImageMaxFileSize: 10000,
        <?php if($limit==1): ?>
        singleFileUploads: true,
        <?php else: ?>
        singleFileUploads: false,
        <?php endif; ?>
        formData: {
            'token': "<?=$token?>",
            'ID': "<?=sha1($token)?>",
            'key': "<?=$limit_key?>"
        },
        dataType: 'json',
        done: function (e, data) {

            var results = data._response.result.results;
            $("#progress<?=$tag_id?>").addClass("hidden");
            $("#progress<?=$tag_id?> .percent").animate({"width": "0%"});
            $(".image-uploaded").removeClass("hidden");

            <?php if($limit==1): ?>
            <?=$array_name?> = {};
            $("#image-previews<?=$tag_id?>").html(results.html);
            $("#image-previews<?=$tag_id?> .item_"+results.image).hide().fadeIn("linear");
            <?php else: ?>
            var new_div = results.html;
            $("#image-previews<?=$tag_id?>").append(new_div);
            $("#image-previews<?=$tag_id?> .item_"+results.image).hide().fadeIn('linear');
            <?php endif; ?>


            $("#image-previews<?=$tag_id?>").animate({
                scrollLeft: "+=120px"
            }, "linear");

                <?=$array_name?>[results.image] = results.image;
            //$("#image-data").val(results.image_data);

            refreshGallery<?=$rand?>();

            $("#image-previews<?=$tag_id?> #delete").on('click', function () {

                var nameDir = $(this).attr("data");
                $("#image-previews<?=$tag_id?> .image-uploaded.item_" + nameDir).fadeOut('linear');
                refreshGallery<?=$rand?>();
                deleteItem<?=$rand?>(nameDir);

                return false;
            });

            if (results.length === 0) {

                var errors = data._response.result.errors;

                var errorMsg = "";
                for (var key in errors) {
                    errorMsg = errorMsg + " # " + errors[key] + "<br>";
                }
                if (errorMsg !== "") {
                    alert(errorMsg);
                }

                refreshGallery<?=$rand?>();
            }

        },
        fail: function (e, data) {

            $("#progress<?=$tag_id?>").addClass("hidden");
            $("#progress<?=$tag_id?> .percent").animate({"width": "0%"});

        },
        progressall: function (e, data) {

            var progress = parseInt(data.loaded / data.total * 100, 10);

            $("#progress<?=$tag_id?>").removeClass("hidden");
            $("#progress<?=$tag_id?> .percent").animate({"width": progress + "%"}, "linear");

        },
        progress: function (e, data) {

            var progress = parseInt(data.loaded / data.total * 100, 10);

        },
        start: function (e) {

            $("#fileupload").removeClass("input-error");
            $(".image-data").text("");

        }
    });

    function deleteItem<?=$rand?>(id) {


        $.ajax({
            url: "<?=  site_url("uploader/ajax/delete")?>",
            data: {
                'id': id,
                'key': "<?=$limit_key?>",
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {

            }, error: function (request, status, error) {
                console.log(request)
            },
            success: function (data, textStatus, jqXHR) {
                console.log(data);
                if(data.success===1){

                    delete <?=$array_name?>[id];
                    $("#image-previews<?=$tag_id?> .image-uploaded.item_" + id).remove();
                    refreshGallery<?=$rand?>();

                }else if(data.success===0){

                    $("#image-previews<?=$tag_id?> .image-uploaded.item_" + id).fadeIn();

                    var errors = data.errors;

                    var errorMsg = "";
                    for (var key in errors) {
                        errorMsg = errorMsg + " # " + errors[key] + "<br>";
                    }
                    if (errorMsg !== "") {
                        alert(errorMsg);
                    }
                }
            }
        });



    }

    function refreshGallery<?=$rand?>() {

        $("#image-previews<?=$tag_id?>").addClass('hidden');

        var length = 0;
        for (var key in <?=$array_name?>){
            length++;
        }


        $('.images-counter<?=$tag_id?> .count-<?= $tag_id ?>').text(length);

        if(length===<?=intval($limit)?>) {
            $('.images-counter<?=$tag_id?>').addClass('text-green');
        }else  if(length><?=intval($limit)?>){
            $('.images-counter<?=$tag_id?>').removeClass('text-green');
            $('.images-counter<?=$tag_id?>').addClass('text-red');
        }else{
            $('.images-counter<?=$tag_id?>').removeClass('text-green');
            $('.images-counter<?=$tag_id?>').removeClass('text-red');
        }

        if(length!==0){

            var new_var_list = {};
            $("#image-previews<?=$tag_id?> ").removeClass('hidden');
            $( "#image-previews<?=$tag_id?> .image-uploaded" ).each(function( index ) {
                var id = $( this ).attr("data-id");
                new_var_list[id] = id;

                $("#image-previews<?=$tag_id?> .item_"+id+" .index").text(parseInt(index+1));

            });

            <?=$array_name?> = new_var_list;

        }

    }

    $( "#image-previews<?=$tag_id?>" ).sortable({
        start: function(e, ui) {

        },
        stop: function() {
            refreshGallery<?=$rand?>();
        }
    });



    //$( "#image-previews<?=$tag_id?>" ).disableSelection();



    function getImages<?=$rand?>(photosURLs){

        $.ajax({
            url: "<?=  site_url("ajax/uploader/uploadURLs")?>",
            data: {
                "URLs": photosURLs,
                'token': "<?=$token?>",
                'ID': "<?=sha1($token)?>",
                'key': "<?=$limit_key?>"
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {

                $("#progress<?=$tag_id?>").removeClass("hidden");
                $("#progress<?=$tag_id?> .percent").animate({"width": "0%"});

            }, error: function (request, status, error) {
                alert(request.responseText);
                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {


                if(data.success===1){

                    var result = data.result;

                    for (var key in result){

                        var percent = parseInt(parseInt(key+1)/result.length)*100;

                        $("#progress<?=$tag_id?> .percent").animate({"width": percent+"%"});

                        var image = result[key];
                        var new_div = image.html;

                        console.log(image);

                        <?php if($limit==1): ?>
                        <?=$array_name?> = {};
                        $("#image-previews<?=$tag_id?>").html(new_div);
                        $("#image-previews<?=$tag_id?> .item_"+image.image).hide().fadeIn("linear");
                        <?php else: ?>
                        $("#image-previews<?=$tag_id?>").append(new_div);
                        $("#image-previews<?=$tag_id?> .item_"+image.image).hide().fadeIn('linear');
                        <?php endif; ?>

                        $("#image-previews<?=$tag_id?>").animate({
                            scrollLeft: "+=120px"
                        }, "linear");

                            <?=$array_name?>[image.image] = image.image;

                        $("#image-previews<?=$tag_id?> #delete").on('click', function () {

                            var nameDir = $(this).attr("data");
                            $("#image-previews<?=$tag_id?> .image-uploaded.item_" + nameDir).fadeOut('linear');
                            refreshGallery<?=$rand?>();
                            deleteItem<?=$rand?>(nameDir);

                            return false;
                        });

                    }

                    setTimeout(function () {
                        $("#progress<?=$tag_id?>").addClass("hidden");
                        //refresh gallery
                        refreshGallery<?=$rand?>();
                    },3000);



                }else{
                    $("#progress<?=$tag_id?>").addClass("hidden");
                    //refresh gallery
                    refreshGallery<?=$rand?>();
                }

            }
        });



    }


    function clearGallery<?=$rand?>(){

        for(var k in <?=$array_name?>){
            deleteItem<?=$rand?>(<?=$array_name?>[k]);
        }

    }


</script>