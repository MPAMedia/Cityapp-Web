<script src="<?= base_url("views/skin/backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/locationpicker/locationpicker.jquery.min.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>

<script src='https://maps.googleapis.com/maps/api/js?key=<?= MAPS_API_KEY ?>&libraries=places'></script>


<script src="<?= base_url("views/skin/backend/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>

<script>

    $.fn.datepicker.defaults.format = "yyyy-mm-dd";
    $('.datepicker').datepicker({
        startDate: '-3d'
    });
</script>

<script>

    var store_id = 0;
    $("#compose-textarea").wysihtml5();

    $("#btnCreate").on('click', function () {

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
            url: "<?=  site_url("ajax/event/create")?>",
            data: {
                "store_id": store_id,
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

                $("#btnCreate").attr("disabled", true);

            }, error: function (request, status, error) {
                alert(request.responseText);
                $("#btnCreate").attr("disabled", false);
                console.log(request)
            },
            success: function (data, textStatus, jqXHR) {

                console.log(data);

                $("#btnCreate").attr("disabled", false);


                if (data.success === 1) {
                    document.location.href = "<?=admin_url("event/events")?>";
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


    $('#somecomponent').locationpicker({
        location: {
            latitude: <?=$lat?>,
            longitude:<?=$lng?>
        },
        radius: 300,
        inputBinding: {
            latitudeInput: $('#lat'),
            longitudeInput: $('#lng'),
            radiusInput: $('#radius'),
            locationNameInput: $('#places')
        },
        enableAutocomplete: true
    });


    $("#btnAdd").on('click', function () {

        var cat = $("#addCat").val();
        $.ajax({
            url: "<?=  site_url("ajax/addGategory")?>",
            data: {'cat': cat},
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                $("#btnAdd").attr("disabled", true);

            }, error: function (request, status, error) {
                alert(request.responseText);
                $("#btnAdd").attr("disabled", false);
            },
            success: function (data, textStatus, jqXHR) {

                console.log(data);

                $("#btnAdd").attr("disabled", false);
                if (data.success === 1) {
                    alert(data.message);
                    location.reload();
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

    //Initialize Select2 Elements

</script>
<script>


    $('.selectStore').select2();
    $('.selectStore').on('select2:select', function (e) {
        // Do something
        var data = e.params.data;
        var id = data.id;
        store_id = id;
        if (id > 0) {

            var adr = $(".selectStore option[value=" + id + "]").attr("adr");
            var lat = $(".selectStore option[value=" + id + "]").attr("lat");
            var lng = $(".selectStore option[value=" + id + "]").attr("lng");

            $("#address").val(adr);
            $("#lat").val(lat);
            $("#lng").val(lng);

        }

    });
</script>


