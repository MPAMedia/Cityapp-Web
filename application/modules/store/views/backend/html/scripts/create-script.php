<script src="<?= base_url("views/skin/backend/plugins/locationpicker/locationpicker.jquery.min.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>
<script type="text/javascript" src='https://maps.googleapis.com/maps/api/js?key=<?= MAPS_API_KEY ?>&libraries=places'></script>
<script>


    $('#somecomponent').locationpicker({
        location: {latitude: <?=$lat?>, longitude:<?=$lng?>},
        radius: 300,
        inputBinding: {
            latitudeInput: $('#lat'),
            longitudeInput: $('#lng'),
            radiusInput: $('#radius'),
            locationNameInput: $('#places')
        },
        enableAutocomplete: true
    });


</script>
<script>


    $("#btnCreate").on('click', function () {

        var name = $("#form #name").val();
        var address = $("#form #address").val();
        var detail = $("#editable-textarea").val();
        var tel = $("#form #tel").val();
        var cat = $("#form #cat").val();

        var lat = $("#form #lat").val();
        var lng = $("#form #lng").val();
        $.ajax({
            url: "<?=  site_url("ajax/store/createStore")?>",
            data: {
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
                $("#btnCreate").attr("disabled", true);

            }, error: function (request, status, error) {
                alert(request.responseText);
                $("#btnCreate").attr("disabled", false);

                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {

                $("#btnCreate").attr("disabled", false);
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

</script>
<script>


    $('.selectCat').select2();


</script>


