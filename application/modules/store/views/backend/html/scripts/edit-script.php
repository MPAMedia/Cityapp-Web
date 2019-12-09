<script src="<?= base_url("views/skin/backend/plugins/locationpicker/locationpicker.jquery.min.js") ?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/select2/select2.full.min.js")?>"></script>
<script type="text/javascript"
        src='https://maps.googleapis.com/maps/api/js?key=<?=MAPS_API_KEY?>&libraries=places'></script>
<script>
    $('#somecomponent').locationpicker({
        location: {
            latitude: <?=$store['latitude']?>, longitude:<?=$store['longitude']?>
        },
        radius: 300,
        inputBinding: {
            latitudeInput: $('#lat'),
            longitudeInput: $('#lng'),
            radiusInput: $('#radius'),
            locationNameInput: $('#places')
        }, enableAutocomplete: true
    });

    <?php if($store['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>



    $("#btnCreate").on('click', function () {

        var selector = $(this);

        var id = $("#form #id").val();
        var name = $("#form #name").val();
        var address = $("#form #address").val();
        var detail = $("#editable-textarea").val();
        var tel = $("#form #tel").val();
        var cat = $("#form #cat").val();

        var lat = $("#form #lat").val();
        var lng = $("#form #lng").val();

        console.log(<?=$uploader_variable?>);

        $.ajax({
            url: "<?=  site_url("ajax/store/edit")?>",
            data: {
                'id': id,
                "name": name,
                "address": address,
                "detail": detail,
                "tel": tel,
                "cat": cat,
                "lat": lat,
                "lng": lng,
                "images": JSON.stringify(<?=$uploader_variable?>),
                <?php if(ModulesChecker::isRegistred("gallery")){ ?>
                "gallery": JSON.stringify(<?=$gallery_variable?>)
                <?php } ?>
            },


            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                selector.attr("disabled", true);

            }, error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled", false);
                console.log(request.responseText);
            },
            success: function (data, textStatus, jqXHR) {


                selector.attr("disabled", false);
                if (data.success === 1) {
                    document.location.href = "<?=admin_url("store/stores")?>";
                } else if (data.success === 0) {
                    var errorMsg = "";
                    for (var key in data.errors) {
                        errorMsg = errorMsg + data.errors[key] + "\n";
                    }
                    if (errorMsg !== "") {
                        alert(errorMsg);
                    }
                }
            }
        });

        return false;

    });
    <?php } ?>


</script>
<script>
    $('.selectCat').select2();
</script>
<?php if(GroupAccess::isGranted('user',USER_ADMIN)): ?>
    <script>


        $("#featured_item1").change(function () {

            var featured = 0;

            if(this.checked)
                featured = 1;
            else
                featured = 0;

            //   alert(featured);

            $.ajax({
                url:"<?=  site_url("ajax/store/markAsFeatured")?>",
                data:{
                    "id": "<?=$store['id_store']?>",
                    "featured": featured,
                    "type": "store"
                },
                dataType: 'json',
                type: 'POST',
                beforeSend: function (xhr) {

                },
                error: function (request, status, error) {
                    console.log(request);
                },
                success: function (data, textStatus, jqXHR) {

                    if(data.success===1){

                        document.location.reload();

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
            return true;
        });


        $("#featured_item0").change(function () {

            var featured = 0;


            $.ajax({
                url:"<?=  site_url("ajax/store/markAsFeatured")?>",
                data:{
                    "id": "<?=$store['id_store']?>",
                    "featured": featured,
                    "type": "store"
                },
                dataType: 'json',
                type: 'POST',
                beforeSend: function (xhr) {

                },
                error: function (request, status, error) {
                    console.log(request);
                },
                success: function (data, textStatus, jqXHR) {

                    if(data.success===1){

                        document.location.reload();

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
            return true;
        });

    </script>
<?php endif;?>
