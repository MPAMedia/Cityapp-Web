<!-- GALLERY -->
<script>

    <?php

    $token = $this->mUserBrowser->setToken($tag."-4555");

    ?>


    Uploader();

    $('#fileuploadbtn-<?=$tag?>').on('click', function() {
        $('#fileuploadinput-<?=$tag?>').trigger('click');
    });

    var fileUploaded_<?=$tag?> = {};

    <?php
    if (!empty($images)) {

        foreach ($images as $key => $value) {

            $name = $value['name'];
            $item = "item_" . $name;


            echo "fileUploaded_".$tag."[" . $name . "]=$name ;";

        }
    }
    ?>


    $("#image-previews-<?=$tag?> #delete").on('click', function () {
        var nameDir = $(this).attr("data");
        delete fileUploaded_<?=$tag?>[nameDir];
        $("#image-previews-<?=$tag?> .image-uploaded-tag-<?=$tag?>.item_" + nameDir).remove();
        console.log(fileUploaded_<?=$tag?>);
        return false;
    });


    function Uploader() {
        //fileuploadinput
        $('#fileuploadinput-<?=$tag?>').fileupload({
            url: "<?=site_url("ajax/uploader/uploadImage")?>",
            sequentialUploads: true,
            formData: {
                'token': "<?=$token?>",
                'ID': "<?=sha1($token)?>"
            },
            dataType: 'json',
            done: function (e, data) {

                var results = data._response.result.results;
                $("#progress-<?=$tag?>").addClass("hidden");
                $("#progress-<?=$tag?> .percent").animate({"width": "0%"});
                $("#image-previews-<?=$tag?> .image-uploaded-tag-<?=$tag?>").removeClass("hidden");
                $("#image-previews-<?=$tag?>").append(results.html);

                console.log(results);

                fileUploaded_<?=$tag?>[results.image] = results.image;
                //$("#image-data").val(results.image_data);


                $("#image-previews-<?=$tag?> #delete").on('click', function () {

                    var nameDir = $(this).attr("data");

                    delete fileUploaded_<?=$tag?>[nameDir];
                    console.log(fileUploaded_<?=$tag?>);


                    $("#image-previews-<?=$tag?> .image-uploaded.item_" + nameDir).remove();


                    return false;
                });

            },
            fail: function (e, data) {

                $("#progress-<?=$tag?>").addClass("hidden");
                $("#progress-<?=$tag?> .percent").animate({"width": "0%"});

                console.log(data);

            },
            progressall: function (e, data) {

                var progress = parseInt(data.loaded / data.total * 100, 10);

                $("#progress-<?=$tag?>").removeClass("hidden");
                $("#progress-<?=$tag?> .percent").animate({"width": progress + "%"}, "linear");


                console.log(progress);


            },
            progress: function (e, data) {


                var progress = parseInt(data.loaded / data.total * 100, 10);


            },
            start: function (e) {

                $("#fileuploadinput-<?=$tag?>").removeClass("input-error");
                $(".image-data-<?=$tag?>").text("");

            }
        });


    }


</script>
<!-- END GALLERY -->