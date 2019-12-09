<script src="<?= base_url("views/skin/backend/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/locationpicker/locationpicker.jquery.min.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>
<script type="text/javascript"
        src='https://maps.googleapis.com/maps/api/js?key=<?= MAPS_API_KEY ?>&libraries=places'></script>

<script>

    $('.selectStore').select2();
    $('.selectStore').on('select2:select', function (e) {
        // Do something
        var data = e.params.data;
        var id = data.id;
        store_id = id;
    });
</script>
<script>
    $.fn.datepicker.defaults.format = "dd-mm-yyyy";
    $('.datepicker').datepicker({
        startDate: '-3d'
    });
</script>


<script>
    $('#somecomponent').locationpicker({
        location: {latitude: <?=$event['lat']?>, longitude:<?=$event['lng']?>},
        radius: 300,
        inputBinding: {
            latitudeInput: $('#lat'),
            longitudeInput: $('#lng'),
            radiusInput: $('#radius'),
            locationNameInput: $('#places')
        },
        enableAutocomplete: true
    });

    var store_id = "<?php if (isset($event['store_id']) AND !empty($event['store_id']))
        echo $event['store_id'];
    else echo -1;  ?>";


    <?php if($event['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>

    $("#btnSave").on('click', function () {

        var selector = $(this);

        var id = $("#form #id").val();
        var name = $("#form #name").val();
        var address = $("#form #address").val();
        var desc = $("#editable-textarea").val();
        var tel = $("#form #tel").val();
        var website = $("#form #web").val();

        var lat = $("#form #lat").val();
        var lng = $("#form #lng").val();

        var date_b = $("#form #date_b").val();
        var date_e = $("#form #date_e").val();


        $.ajax({
            url: "<?=  site_url("ajax/event/edit")?>",
            data: {
                "store_id": store_id,
                'id': id,
                "name": name,
                "address": address,
                "desc": desc,
                "tel": tel,
                "website": website,
                "lat": lat,
                "lng": lng,
                "date_b": date_b,
                "date_e": date_e,
                "images": JSON.stringify(<?=$uploader_variable?>)
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                selector.attr("disabled", true);

            }, error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled", false);
                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {


                selector.attr("disabled", false);
                if (data.success === 1) {
                    document.location.href = data.url;

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



<?php if (GroupAccess::isGranted('user',USER_ADMIN)): ?>
    <script>

        $("#featured_item1").change(function () {

            var featured = 0;

            if (this.checked)
                featured = 1;
            else
                featured = 0;

            //   alert(featured);

            $.ajax({
                url: "<?=  site_url("ajax/event/markAsFeatured")?>",
                data: {
                    "id": "<?=$event['id_event']?>",
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

                    if (data.success === 1) {

                        document.location.reload();

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
            return true;
        });


        $("#featured_item0").change(function () {

            var featured = 0;


            $.ajax({
                url: "<?=  site_url("ajax/event/markAsFeatured")?>",
                data: {
                    "id": "<?=$event['id_event']?>",
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

                    if (data.success === 1) {

                        document.location.reload();

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
            return true;
        });

    </script>

<?php endif; ?>