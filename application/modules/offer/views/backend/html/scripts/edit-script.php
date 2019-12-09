<!-- page script -->
<script src="<?= base_url("views/skin/backend/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>
<script>

    $.fn.datepicker.defaults.format = "yyyy-mm-dd";
    $('.datepicker').datepicker({
        startDate: '-3d'
    });

    <?php
    $token = $this->mUserBrowser->setToken("SU74aQ55");
    ?>

    $("#btnSave").on('click', function () {

        var selector = $(this);
        var description = $("#form #editable-textarea").val();
        var price = parseFloat($("#form #priceInput").val());
        var percent = parseFloat($("#form #percentInput").val());
        var date_b = $("#form #date_b").val();
        var date_e = $("#form #date_e").val();
        var name = $("#form #name").val();
        var currency = $("#form #selectCurrency").val();
        var store_id = $("#form #selectStore").val();

        if( parseInt($("#value_type").val()) ===1){
            percent = 0;
        }else{
            price = 0;
        }

        var dataSet0 = {
            "token": "<?=$token?>",
            "store_id": store_id,
            "images": <?=$uploader_variable?>,
            "name": name,
            "description": description,
            "price": price,
            "percent": percent,
            "date_start": date_b,
            "date_end": date_e,
            "currency": currency,
            "offer_id": <?=$offer['id_offer']?>
        };


        $.ajax({
            url: "<?=  site_url("ajax/offer/edit")?>",
            data: dataSet0,
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {

                selector.attr("disabled", true);

            }, error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled", false);
                console.log(request)
            },
            success: function (data, textStatus, jqXHR) {

                selector.attr("disabled", false);
                if (data.success === 1) {
                    document.location.href = "<?=admin_url("offer/offers")?>";
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


    $('#selectCurrency').select2();
    $('#selectStore').select2();
    $('#value_type').select2();

    <?php if($offer['value_type']=='price'): ?>

        $('#value_type').val(1).trigger('change');

        <?php if(isset($offer['currency']['code'])): ?>
        $('#selectCurrency').val("<?=$offer['currency']['code']?>").trigger('change');
        <?php else: ?>
        $('#selectCurrency').val("<?=$offer['currency']?>").trigger('change');
        <?php endif; ?>

        $(".pricing .form-price").removeClass('hidden');
        $(".pricing .form-percent").addClass('hidden');
        $("#percentInput").val('');

    <?php else: ?>

        $('#value_type').val(2).trigger('change');
        $(".pricing .form-price").addClass('hidden');
        $(".pricing .form-percent").removeClass('hidden');
        $("#priceInput").val('');

    <?php endif; ?>

    $('#selectStore').val(<?=$offer['store_id']?>).trigger('change');

    $('#value_type').on('change', function () {

        var value = parseInt($(this).val());

        if (value === 1) {
            $(".pricing .form-price").removeClass('hidden');
            $(".pricing .form-percent").addClass('hidden');
        } else if (value === 2) {
            $(".pricing .form-price").addClass('hidden');
            $(".pricing .form-percent").removeClass('hidden');
        } else {
            $(".pricing .form-price").addClass('hidden');
            $(".pricing .form-percent").addClass('hidden');
            $("#priceInput").val('');
            $("#percentInput").val('');
        }

        return true;
    });




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
                url: "<?=  site_url("ajax/offer/markAsFeatured")?>",
                data: {
                    "id": "<?=$offer['id_offer']?>",
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
                url: "<?=  site_url("ajax/offer/markAsFeatured")?>",
                data: {
                    "id": "<?=$offer['id_offer']?>",
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


